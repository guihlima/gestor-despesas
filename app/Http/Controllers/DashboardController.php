<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Installment;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- IMPORTANTE

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // mês no formato "YYYY-MM"
        $month = $request->input('mes', now()->format('Y-m'));

        try {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) {
            $start = now()->startOfMonth();
            $month = $start->format('Y-m');
        }

        $end = $start->copy()->endOfMonth();

        // ENTRADAS
        $incomesTotal = Income::whereBetween('date', [$start, $end])->sum('amount');

        // SAÍDAS À VISTA NO MÊS
        $expensesAtSight = Expense::whereBetween('date', [$start, $end])
            ->where('is_installment', false)
            ->sum('total_amount');

        // PARCELAS PAGAS NO MÊS
        $paidInstallments = Installment::where('is_paid', true)
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');

        $cashOutTotal = $expensesAtSight + $paidInstallments;

        // PARCELAS EM ABERTO COM VENCIMENTO NO MÊS
        $openInstallmentsDue = Installment::where('is_paid', false)
            ->whereBetween('due_date', [$start, $end])
            ->sum('amount');

        $balance = $incomesTotal - $cashOutTotal;

        $latestIncomes = Income::whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $latestExpenses = Expense::with('bank')
            ->whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // 👇 AQUI GARANTE QUE $start, $end, $month etc vão para a view
        return view('dashboard.index', compact(
            'month',
            'start',
            'end',
            'incomesTotal',
            'cashOutTotal',
            'openInstallmentsDue',
            'balance',
            'latestIncomes',
            'latestExpenses'
        ));
    }
}
