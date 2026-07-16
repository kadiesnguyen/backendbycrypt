<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_admin';

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
        'email',
        'username',
        'nickname',
        'moble', // Lưu ý: có thể là typo của 'mobile'
        'password',
        'sort',
        'addtime',
        'last_login_time',
        'last_login_ip',
        'endtime',
        'status',
        'level',
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
        'sort' => 'integer',
        'addtime' => 'integer',
        'last_login_time' => 'integer',
        'last_login_ip' => 'integer',
        'endtime' => 'integer',
        'status' => 'integer',
        'level' => 'integer',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'guard' => 'admin',
            'level' => (int) $this->level,
        ];
    }

    public function authGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            AuthGroup::class,
            'tw_auth_group_access',
            'uid',
            'group_id'
        );
    }
}