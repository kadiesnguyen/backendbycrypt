<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_recharge';

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
        'method',
        'uid',
        'username',
        'coin',
        'num',
        'num_real',
        'address',
        'addtime',
        'updatetime',
        'status',
        'payimg',
        'msg'
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
        'num' => 'decimal:8',
        'status' => 'integer',
        'addtime' => 'datetime',
        'updatetime' => 'datetime',
    ];

    /**
     * Get the user that owns the recharge.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}