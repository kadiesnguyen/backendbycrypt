<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daohang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_daohang';

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
        'lang',
        'name',
        'title',
        'url',
        'sort',
        'addtime',
        'endtime',
        'status',
        'get_login',
        'access'
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
        'get_login' => 'boolean',
        'access' => 'boolean',
    ];
}