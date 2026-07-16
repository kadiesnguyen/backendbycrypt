<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ctmarket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_ctmarket';

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
        'coinname',
        'name',
        'symbol',
        'title',
        'status',
        'state',
        'sort',
        'addtime',
        'logo'
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
        'status' => 'integer',
        'state' => 'integer',
        'sort' => 'integer',
        'addtime' => 'datetime',
    ];
}