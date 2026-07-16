<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orepool extends Model
{
    protected $table = 'tw_orepool';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'oretitle',
        'oreimg',
        'summoney',
        'fmoney',
        'minmoney',
        'maxmoney',
        'coinname',
        'cc_coin',
        'rtype',
        'status',
        'addtime',
        'buytype',
        'arrmoney',
        'buynum',
        'rway',
        'sfbl',
        'gdnum',
        'gdbl',
        'sort',
        'allmoney',
    ];

    protected $casts = [
        'summoney' => 'decimal:2',
        'fmoney' => 'decimal:2',
        'minmoney' => 'decimal:2',
        'maxmoney' => 'decimal:2',
        'rtype' => 'integer',
        'status' => 'integer',
        'buytype' => 'integer',
        'buynum' => 'integer',
        'rway' => 'integer',
        'sfbl' => 'decimal:2',
        'gdnum' => 'decimal:2',
        'sort' => 'integer',
        'allmoney' => 'decimal:2',
        'addtime' => 'datetime',
    ];
}
