<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerpFill extends Model
{
    protected $table = 'tw_perp_fill';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'uid',
        'position_id',
        'symbol',
        'side',
        'action',
        'qty',
        'price',
        'leverage',
        'margin_delta',
        'fee',
        'pnl',
        'created_at',
    ];

    protected $casts = [
        'uid' => 'integer',
        'position_id' => 'integer',
        'qty' => 'float',
        'price' => 'float',
        'leverage' => 'integer',
        'margin_delta' => 'float',
        'fee' => 'float',
        'pnl' => 'float',
        'created_at' => 'datetime',
    ];
}
