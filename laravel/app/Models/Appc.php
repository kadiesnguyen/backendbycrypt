<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appc extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_appc';

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
        'web_name',
        'web_title',
        'web_icp',
        'index_img',
        'pay',
        'withdraw_notice',
        'charge_notice',
        'show_coin',
        'show_market'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}