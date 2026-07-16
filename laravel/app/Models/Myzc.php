<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Myzc extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_myzc';

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
        'userid',
        'username',
        'wallet',
        'coinname',
        'txid',
        'num',
        'fee',
        'mum',
        'address',
        'sort',
        'addtime',
        'endtime',
        'status',
        'to_user'
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
        'userid' => 'integer',
        'num' => 'decimal:8',
        'fee' => 'decimal:8',
        'mum' => 'decimal:8',
        'sort' => 'integer',
        'addtime' => 'datetime',
        'endtime' => 'datetime',
        'status' => 'integer',
    ];

    /**
     * Get the user that owns the transfer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}