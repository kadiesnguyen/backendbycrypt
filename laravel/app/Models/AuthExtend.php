<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthExtend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_auth_extend';

    /**
     * The primary key for the model (composite key).
     *
     * @var string|null
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'extend_id',
        'type'
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
        'group_id' => 'integer',
        'extend_id' => 'integer',
        'type' => 'integer',
    ];

    /**
     * Get the auth group that owns the extend.
     */
    public function authGroup()
    {
        return $this->belongsTo(AuthGroup::class, 'group_id');
    }
}