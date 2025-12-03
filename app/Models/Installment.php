<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'expense_id',
        'installment_number',
        'total_installments',
        'amount',
        'due_date',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
