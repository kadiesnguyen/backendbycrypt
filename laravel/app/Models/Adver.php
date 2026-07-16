<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_adver';

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
        'subhead',
        'url',
        'img',
        'type',
        'sort',
        'addtime',
        'endtime',
        'onlinetime',
        'status',
        'look'
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
        'onlinetime' => 'integer',
        'status' => 'integer',
        'look' => 'boolean',
    ];
}