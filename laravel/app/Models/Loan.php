<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REPAID = 'repaid';
    public const STATUS_OVERDUE = 'overdue';

    /** @var list<string> */
    public const OPEN_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ACTIVE,
        self::STATUS_OVERDUE,
    ];

    protected $table = 'tw_loan';

    protected $fillable = [
        'user_id',
        'username',
        'amount',
        'duration_days',
        'daily_interest_rate',
        'lender_name',
        'interest_amount',
        'repay_amount',
        'status',
        'note',
        'img_front',
        'img_back',
        'approved_at',
        'due_at',
        'repaid_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'amount' => 'decimal:8',
        'duration_days' => 'integer',
        'daily_interest_rate' => 'decimal:10',
        'interest_amount' => 'decimal:8',
        'repay_amount' => 'decimal:8',
        'approved_at' => 'datetime',
        'due_at' => 'datetime',
        'repaid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
