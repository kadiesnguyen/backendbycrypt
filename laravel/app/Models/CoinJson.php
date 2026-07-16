<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinJson extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_coin_json';

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
        'data',
        'type',
        'sort',
        'addtime',
        'endtime',
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
        'sort' => 'integer',
        'addtime' => 'integer',
        'endtime' => 'integer',
        'status' => 'integer',
    ];
}