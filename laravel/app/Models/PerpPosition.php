<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerpPosition extends Model
{
    public const STATUS_OPEN = 1;

    public const STATUS_CLOSED = 2;

    public const STATUS_LIQUIDATED = 3;

    protected $table = 'tw_perp_position';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'uid',
        'username',
        'symbol',
        'side',
        'qty',
        'entry_price',
        'leverage',
        'margin',
        'liq_price',
        'unrealized_pnl',
        'status',
        'opened_at',
        'closed_at',
        'close_price',
        'realized_pnl',
        'kongyk',
        'admin_notified',
    ];

    protected $casts = [
        'uid' => 'integer',
        'qty' => 'float',
        'entry_price' => 'float',
        'leverage' => 'integer',
        'margin' => 'float',
        'liq_price' => 'float',
        'unrealized_pnl' => 'float',
        'status' => 'integer',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'close_price' => 'float',
        'realized_pnl' => 'float',
        'kongyk' => 'integer',
        'admin_notified' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
