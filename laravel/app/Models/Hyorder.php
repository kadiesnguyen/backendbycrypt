<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hyorder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_hyorder';

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
        'uid',
        'username',
        'num',
        'hybl',
        'hyzd',
        'coinname',
        'status',
        'is_win',
        'buytime',
        'selltime',
        'intselltime',
        'buyprice',
        'sellprice',
        'ploss',
        'time',
        'kongyk',
        'invit',
        'tznum'
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
        'uid' => 'integer',
        'num' => 'float',
        'hybl' => 'float',
        'hyzd' => 'integer',
        'status' => 'integer',
        'is_win' => 'integer',
        'buytime' => 'datetime',
        'selltime' => 'datetime',
        'intselltime' => 'integer',
        'buyprice' => 'decimal:2',
        'sellprice' => 'decimal:2',
        'ploss' => 'decimal:2',
        'time' => 'integer',
        'kongyk' => 'integer',
        'tznum' => 'integer',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}