<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_footer';

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
        'img',
        'type',
        'remark',
        'sort',
        'addtime',
        'endtime',
        'status',
        'get_login'
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
    ];
}