<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_user_coin';

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
        'usdt',
        'usdt_d',
        'btc',
        'btc_d',
        'eth',
        'eth_d',
        'ltc',
        'ltc_d',
        'sol',
        'sol_d',
        'xrp',
        'xrp_d',
        'uni',
        'uni_d',
        'xau',
        'xau_d',
        'bch',
        'bch_d',
        'dot',
        'dot_d',
        'trb',
        'trb_d',
        'trx',
        'trx_d',
        'trump',
        'trump_d',
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
        'usdt' => 'decimal:10',
        'usdt_d' => 'decimal:10',
        'btc' => 'decimal:10',
        'btc_d' => 'decimal:10',
        'eth' => 'decimal:10',
        'eth_d' => 'decimal:10',
        'ltc' => 'decimal:10',
        'ltc_d' => 'decimal:10',
        'sol' => 'decimal:10',
        'sol_d' => 'decimal:10',
        'xrp' => 'decimal:10',
        'xrp_d' => 'decimal:10',
        'uni' => 'decimal:10',
        'uni_d' => 'decimal:10',
        'xau' => 'decimal:10',
        'xau_d' => 'decimal:10',
        'bch' => 'decimal:10',
        'bch_d' => 'decimal:10',
        'dot' => 'decimal:10',
        'dot_d' => 'decimal:10',
        'trb' => 'decimal:10',
        'trb_d' => 'decimal:10',
        'trx' => 'decimal:10',
        'trx_d' => 'decimal:10',
        'trump' => 'decimal:10',
        'trump_d' => 'decimal:10',
    ];

    /**
     * Get the user that owns the coin balance.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}