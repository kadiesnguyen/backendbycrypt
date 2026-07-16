<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_market';

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
        'name',
        'round',
        'round_mum',
        'buy_min',
        'buy_max',
        'sell_min',
        'sell_max',
        'trade_min',
        'trade_max',
        'zhang',
        'die',
        'hou_price',
        'tendency',
        'trade',
        'new_price',
        'buy_price',
        'sell_price',
        'min_price',
        'max_price',
        'volume',
        'change',
        'api_min',
        'api_max',
        'sort',
        'addtime',
        'endtime',
        'status',
        'trade_buy_num_min',
        'trade_buy_num_max',
        'trade_sell_num_min',
        'trade_sell_num_max',
        'fshow',
        'shuadan',
        'faxingjia',
        'sdhigh',
        'sdlow',
        'sdhigh_num',
        'sdlow_num',
        'fee_buy',
        'fee_sell',
        'jiaoyiqu',
        'invit_1',
        'invit_2',
        'invit_3',
        'invit_buy',
        'invit_sell',
        'start_time',
        'start_minute',
        'stop_time',
        'stop_minute',
        'agree6',
        'agree7',
        'sdtype',
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
        'trade' => 'integer',
        'new_price' => 'decimal:2',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'volume' => 'decimal:2',
        'change' => 'decimal:2',
        'api_min' => 'decimal:2',
        'api_max' => 'decimal:2',
        'sort' => 'integer',
        'addtime' => 'integer',
        'endtime' => 'integer',
        'status' => 'integer',
        'fshow' => 'boolean',
        'faxingjia' => 'decimal:2',
    ];
}