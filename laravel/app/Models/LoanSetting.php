<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSetting extends Model
{
    protected $table = 'tw_loan_setting';

    public $timestamps = false;

    protected $fillable = [
        'enabled',
        'min_amount',
        'max_amount',
        'duration_days',
        'daily_interest_rate',
        'lender_name',
        'updated_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'min_amount' => 'decimal:8',
        'max_amount' => 'decimal:8',
        'duration_days' => 'integer',
        'daily_interest_rate' => 'decimal:10',
        'updated_at' => 'datetime',
    ];
}
