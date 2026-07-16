<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_transfer_history';

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
        'coinid',
        'coinname',
        'amount',
        'from',
        'to',
        'addtime',
        'status',
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
        'coinid' => 'integer',
        'amount' => 'decimal:10',
        'status' => 'integer',
        'addtime' => 'datetime',
    ];

    /**
     * Get the user that owns this transfer history record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
