<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinExchangeHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_coin_exchange_history';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid',
        'username',
        'from_coin',
        'to_coin',
        'from_amount',
        'to_amount',
        'from_rate_usdt',
        'to_rate_usdt',
        'usdt_amount',
        'addtime',
        'status',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'userid' => 'integer',
        'from_amount' => 'decimal:10',
        'to_amount' => 'decimal:10',
        'from_rate_usdt' => 'decimal:10',
        'to_rate_usdt' => 'decimal:10',
        'usdt_amount' => 'decimal:10',
        'status' => 'integer',
        'addtime' => 'datetime',
    ];

    /**
     * Get the user that owns this exchange history record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
