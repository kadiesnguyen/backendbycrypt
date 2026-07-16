<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kjorder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_kjorder';

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
        'kid',
        'sharbltxt',
        'type',
        'sharebl',
        'uid',
        'username',
        'kjtitle',
        'imgs',
        'status',
        'cycle',
        'synum',
        'outtype',
        'outcoin',
        'outnum',
        'outusdt',
        'djout',
        'djnum',
        'addtime',
        'endtime',
        'intaddtime',
        'intendtime',
        'last_earning_at',
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
        'kid' => 'integer',
        'sharbltxt' => 'integer',
        'type' => 'integer',
        'sharebl' => 'float',
        'uid' => 'integer',
        'status' => 'integer',
        'cycle' => 'integer',
        'synum' => 'integer',
        'outtype' => 'integer',
        'outnum' => 'decimal:2',
        'outusdt' => 'decimal:2',
        'djout' => 'integer',
        'djnum' => 'integer',
        'addtime' => 'datetime',
        'endtime' => 'datetime',
        'intaddtime' => 'integer',
        'intendtime' => 'integer',
        'last_earning_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * Get the kuangji that the order belongs to.
     */
    public function kuangji()
    {
        return $this->belongsTo(Kuangji::class, 'kid');
    }

    /**
     * Get the profits for the order.
     */
    public function profits()
    {
        return $this->hasMany(Kjprofit::class, 'kid');
    }
}