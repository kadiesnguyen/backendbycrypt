<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_auth_group';

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
        'module',
        'type',
        'title',
        'description',
        'status',
        'rules'
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
        'type' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Get the users associated with the group through access.
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, AuthGroupAccess::class, 'group_id', 'id', 'id', 'uid');
    }

    /**
     * Get the extends for the group.
     */
    public function extends()
    {
        return $this->hasMany(AuthExtend::class, 'group_id');
    }
}