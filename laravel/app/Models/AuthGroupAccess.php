<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroupAccess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_auth_group_access';

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
        'group_id'
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
        'group_id' => 'integer',
    ];

    /**
     * Get the user that owns the access.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * Get the auth group that owns the access.
     */
    public function authGroup()
    {
        return $this->belongsTo(AuthGroup::class, 'group_id');
    }
}