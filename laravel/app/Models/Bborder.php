<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bborder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_bborder';

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
        'uid',
        'account',
        'type',
        'ordertype',
        'symbol',
        'coin',
        'coinnum',
        'usdtnum',
        'price',
        'xjprice',
        'addtime',
        'tradetime',
        'fee',
        'sxfbl',
        'status'
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
        'uid' => 'integer',
        'type' => 'integer',
        'ordertype' => 'integer',
        'coinnum' => 'decimal:2',
        'usdtnum' => 'decimal:2',
        'price' => 'decimal:2',
        'xjprice' => 'decimal:2',
        'fee' => 'decimal:2',
        'sxfbl' => 'float',
        'status' => 'integer',
        'addtime' => 'datetime',
        'tradetime' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}