<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_coin';

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
        'czline',
        'type',
        'title',
        'sort',
        'addtime',
        'status',
        'czstatus',
        'czaddress',
        'czminnum',
        'txstatus',
        'sxftype',
        'txsxf',
        'txsxf_n',
        'txminnum',
        'txmaxnum',
        'bbsxf',
        'hysxf',
        'bank',
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
        'type' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'czstatus' => 'integer',
        'czminnum' => 'float',
        'txstatus' => 'integer',
        'sxftype' => 'integer',
        'txsxf' => 'float',
        'txsxf_n' => 'float',
        'txminnum' => 'float',
        'txmaxnum' => 'float',
        'bbsxf' => 'float',
        'hysxf' => 'float',
        'addtime' => 'datetime',
        'bank' => 'float',
    ];

    /**
     * Get the comments for the coin.
     */
    public function comments()
    {
        return $this->hasMany(CoinComment::class, 'coinname', 'name');
    }
}