<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'description',
        'total_amount',
        'date',
        'is_installment',
        'bank_id',
        'is_paid',
        'paid_at',
    ];


    protected $casts = [
        'date' => 'date',
        'is_installment' => 'boolean',
    ];

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
