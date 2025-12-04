<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Installment;
use App\Models\Bank;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{

    public function index(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year',  now()->year);

        // 1) Despesas à vista do mês (o que vence nesse mês e ainda devo)
        $cashExpenses = Expense::with('bank')
            ->where('is_installment', false)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            // se tiver campo is_paid na despesa, filtra aqui:
            //->where('is_paid', false)
            ->get();

        // 2) Parcelas que vencem nesse mês (de compras parceladas)
        $installments = Installment::with(['expense.bank'])
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->where('is_paid', false)   // só o que ainda devo
            ->get();

        // 3) Total que devo no mês (à vista + parcelas em aberto)
        $totalMonth = $cashExpenses->sum('total_amount')
            + $installments->sum('amount');

        // 4) Totais por banco/cartão (considerando os dois tipos)
        $totalsByBank = [];

        foreach ($cashExpenses as $expense) {
            $name = $expense->bank->name ?? 'Sem banco';
            $totalsByBank[$name] = ($totalsByBank[$name] ?? 0) + $expense->total_amount;
        }

        foreach ($installments as $installment) {
            $name = $installment->expense->bank->name ?? 'Sem banco';
            $totalsByBank[$name] = ($totalsByBank[$name] ?? 0) + $installment->amount;
        }

        ksort($totalsByBank);

        return view('expenses.index', compact(
            'cashExpenses',
            'installments',
            'totalMonth',
            'totalsByBank',
            'month',
            'year',
        ));
    }


    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        return view('expenses.create', compact('banks'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        // return
        $rules = [
            'description'     => 'required|string|max:255',
            'total_amount'    => 'required|string',
            'date'            => 'required|date',
            'is_installment'  => 'nullable|boolean',
            'bank_id'         => 'nullable|exists:banks,id',
        ];

        if ($request->boolean('is_installment')) {
            $rules['installments_count']        = 'required|integer|min:2';
            $rules['first_due_date']            = 'required|date';
            $rules['installment_amounts']       = 'nullable|array';
            $rules['installment_amounts.*']     = 'nullable|string';
            $rules['installment_due_dates']     = 'nullable|array';
            $rules['installment_due_dates.*']   = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $totalAmount = $this->parseMoney($validated['total_amount']);
        $isInstallment = $request->boolean('is_installment');

        $expense = Expense::create([
            'description'    => $validated['description'],
            'total_amount'   => $totalAmount,
            'date'           => $validated['date'],
            'is_installment' => $isInstallment,
            'bank_id'        => $validated['bank_id'] ?? null,
        ]);

        if ($isInstallment) {
            $installmentsCount = (int) $validated['installments_count'];
            $firstDueDate = \Carbon\Carbon::parse($validated['first_due_date']);

            $amounts = $request->input('installment_amounts', []);
            $dueDates = $request->input('installment_due_dates', []);

            // Se o front gerou direitinho N parcelas, usa os valores informados
            if (count($amounts) === $installmentsCount && count($dueDates) === $installmentsCount) {
                for ($i = 0; $i < $installmentsCount; $i++) {
                    $amount = $this->parseMoney($amounts[$i] ?? '0');
                    $dueDate = $dueDates[$i] ?? $firstDueDate->copy()->addMonthsNoOverflow($i);

                    \App\Models\Installment::create([
                        'expense_id'         => $expense->id,
                        'installment_number' => $i + 1,
                        'total_installments' => $installmentsCount,
                        'amount'             => $amount,
                        'due_date'           => $dueDate,
                        'is_paid'            => false,
                        'paid_at'            => null,
                    ]);
                }
            } else {
                // fallback: divide automaticamente como antes
                $amountPerInstallment = round($totalAmount / $installmentsCount, 2);

                for ($i = 1; $i <= $installmentsCount; $i++) {
                    $dueDate = $firstDueDate->copy()->addMonthsNoOverflow($i - 1);

                    \App\Models\Installment::create([
                        'expense_id'         => $expense->id,
                        'installment_number' => $i,
                        'total_installments' => $installmentsCount,
                        'amount'             => $amountPerInstallment,
                        'due_date'           => $dueDate,
                        'is_paid'            => false,
                        'paid_at'            => null,
                    ]);
                }
            }
        }

        return redirect()
            ->route('despesas.index')
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function pay(Expense $expense)
    {
        // Marca a despesa como paga
        $expense->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('despesas.index')
            ->with('success', 'Despesa paga com sucesso!');
    }

    public function destroy(Expense $expense)
    {
        // Se quiser impedir exclusão com parcelas pagas, dá pra checar aqui.
        // Exemplo (opcional):
        // if ($expense->installments()->where('is_paid', true)->exists()) {
        //     return back()->with('error', 'Não é possível excluir uma despesa com parcelas pagas.');
        // }

        // Apaga as parcelas vinculadas (se não tiver cascade no banco)
        $expense->installments()->delete();

        // Apaga a despesa
        $expense->delete();

        return redirect()
            ->route('despesas.index')
            ->with('success', 'Despesa excluída com sucesso!');
    }


    private function parseMoney(string $value): float
    {
        $value = trim($value);

        if ($value === '') {
            return 0.0;
        }

        // Caso 1: formato BR "1.234,56"
        if (str_contains($value, ',')) {
            // remove separador de milhar e troca vírgula por ponto
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);

            return (float) $value;   // "1.234,56" -> "1234.56" -> 1234.56
        }

        // Caso 2: tem ponto mas sem vírgula -> já é decimal "50.00"
        if (str_contains($value, '.')) {
            return (float) $value;   // "50.00" -> 50.0
        }

        // Caso 3: só dígitos -> inteiro em reais "500"
        return (float) $value;       // "500" -> 500.0
    }
}
