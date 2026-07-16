<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Online extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_online';

    /**
     * The primary key for the model.
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
        'type',
        'content',
        'addtime',
        'state'
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
        'type' => 'integer',
        'addtime' => 'datetime',
        'state' => 'integer',
    ];

    /**
     * Get the user that owns the online record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}