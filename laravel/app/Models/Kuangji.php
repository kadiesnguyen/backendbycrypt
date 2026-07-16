<?php

namespace App\Models;

use App\Casts\ConfigImageCast;
use Illuminate\Database\Eloquent\Model;

class Kuangji extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_kuangji';

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
        'type',
        'rtype',
        'sharebl',
        'sharecode',
        'title',
        'content',
        'imgs',
        'outtype',
        'dayoutnum',
        'outcoin',
        'pricenum',
        'pricecoin',
        'cycle',
        'suanl',
        'allnum',
        'ycnum',
        'sellnum',
        'jlnum',
        'jlcoin',
        'buyask',
        'asknum',
        'djout',
        'djday',
        'status',
        'buymax',
        'addtime'
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
        'rtype' => 'integer',
        'dayoutnum' => 'decimal:2',
        'pricenum' => 'decimal:2',
        'cycle' => 'integer',
        'suanl' => 'float',
        'allnum' => 'integer',
        'ycnum' => 'integer',
        'sellnum' => 'integer',
        'jlnum' => 'decimal:2',
        'buyask' => 'integer',
        'asknum' => 'integer',
        'djout' => 'integer',
        'djday' => 'integer',
        'status' => 'integer',
        'buymax' => 'integer',
        'addtime' => 'datetime',
        'imgs' => ConfigImageCast::class,
    ];

    /**
     * Get the orders for the kuangji.
     */
    public function orders()
    {
        return $this->hasMany(Kjorder::class, 'kid');
    }
}