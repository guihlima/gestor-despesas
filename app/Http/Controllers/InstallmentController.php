<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstallmentController extends Controller
{
    public function index(Expense $expense)
    {
        $installments = $expense->installments()
            ->orderBy('installment_number')
            ->get();

        $paidCount   = $installments->where('is_paid', true)->count();
        $openCount   = $installments->where('is_paid', false)->count();
        $totalAmount = $installments->sum('amount');
        $paidAmount  = $installments->where('is_paid', true)->sum('amount');
        $openAmount  = $installments->where('is_paid', false)->sum('amount');

        return view('installments.index', compact(
            'expense',
            'installments',
            'paidCount',
            'openCount',
            'totalAmount',
            'paidAmount',
            'openAmount'
        ));
    }

    public function pay(Installment $installment)
    {
        if (! $installment->is_paid) {
            $installment->is_paid = true;
            $installment->paid_at = Carbon::now();
            $installment->save();
        }

        return back()->with('success', 'Parcela marcada como paga.');
    }
}
