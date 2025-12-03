<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'description',
        'amount',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
