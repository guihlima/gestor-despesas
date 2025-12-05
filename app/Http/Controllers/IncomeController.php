<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        // Busca todas as receitas e soma o total
        $incomes = Income::orderBy('date', 'desc')->get();
        $total = $incomes->sum('amount');

        return view('incomes.index', compact('incomes', 'total'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        // Agora a validação trata amount como string (com . e ,)
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|string', // <- mudou aqui
            'date'        => 'required|date',
        ]);

        $amount = $this->parseMoney($validated['amount']);

        Income::create([
            'description' => $validated['description'],
            'amount'      => $amount,
            'date'        => $validated['date'],
        ]);

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita cadastrada com sucesso!');
    }

    public function edit(Income $income)
    {
        // Mostra o formulário de edição
        return view('incomes.edit', compact('income'));
    }

    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|string',
            'date'        => 'required|date',
        ]);

        $amount = $this->parseMoney($validated['amount']);

        $income->update([
            'description' => $validated['description'],
            'amount'      => $amount,
            'date'        => $validated['date'],
        ]);

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita atualizada com sucesso!');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita excluída com sucesso!');
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
