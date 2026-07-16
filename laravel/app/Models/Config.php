<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\ConfigImageCast;

class Config extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_config';

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
        'webname',
        'webtitle',
        'weblogo',
        'waplogo',
        'webswitch',
        'websildea',
        'websildeb',
        'websildec',
        'wapsilded',
        'webissue',
        'webkj',
        'wapsildea',
        'wapsildeb',
        'wapsildec',
        'wapissue',
        'wapkj',
        'webtjimgs',
        'waptjimgs',
        'smsemail',
        'emailcode',
        'smstemple',
        'tgtext',
        'gfemail',
        'footertext',
        'regswitch',
        'tbswitch',
        'regjl',
        'tymoney',
        'kefu',
        'appeal',
        'bank_name',
        'bank_acc_no',
        'bank_acc_name',
        'checkin_rewards',
        'checkin_notify',
        'checkin_notify_status',
        'telegram',
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
        'webswitch' => 'integer',
        'regswitch' => 'integer',
        'tbswitch' => 'integer',
        'regjl' => 'integer',
        'checkin_notify_status' => 'integer',
        'tymoney' => 'decimal:2',
        'weblogo' => ConfigImageCast::class,
        'waplogo' => ConfigImageCast::class,
        'websildea' => ConfigImageCast::class,
        'websildeb' => ConfigImageCast::class,
        'websildec' => ConfigImageCast::class,
        'wapsilded' => ConfigImageCast::class,
        'webissue' => ConfigImageCast::class,
        'webkj' => ConfigImageCast::class,
        'wapsildea' => ConfigImageCast::class,
        'wapsildeb' => ConfigImageCast::class,
        'wapsildec' => ConfigImageCast::class,
        'wapissue' => ConfigImageCast::class,
        'wapkj' => ConfigImageCast::class,
        'webtjimgs' => ConfigImageCast::class,
        'waptjimgs' => ConfigImageCast::class,
    ];
}