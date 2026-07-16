<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hysetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_hysetting';

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
        'hy_sxf',
        'hy_time',
        'hy_ykbl',
        'hy_tzed',
        'hy_min_per_frame',
        'hy_max_per_frame',
        'hy_kstime',
        'hy_min',
        'hy_ksid',
        'hy_ylid',
        'hy_fkgl',
        'checkin_rewards',
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
        'hy_sxf' => 'float',
    ];
}