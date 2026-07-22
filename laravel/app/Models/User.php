<?php

namespace App\Models;

use App\Casts\ImageCast;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, HasFactory;

    public const DEFAULT_TRADE_LOCK_MESSAGE = 'Tài khoản đang bị khóa giao dịch';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_user';

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
        'firstname',
        'lastname',
        'gender',
        'dob',
        'country',
        'phonenumber',
        'loan',
        'img_loan',
        'fullname',
        'username',
        'ui_locale',
        'cccd',
        'password',
        'paypassword',
        'cardzm',
        'cardfm',
        'rzstatus',
        'hy_result_mode',
        'level',
        'invit_1',
        'invit_2',
        'invit_3',
        'path',
        'logins',
        'addip',
        'addr',
        'addtime',
        'endtime',
        'lgtime',
        'loginip',
        'loginaddr',
        'logintime',
        'rztime',
        'rzuptime',
        'status',
        'wdstatus',
        'txstate',
        'trade_locked',
        'trade_lock_msg',
        'invit',
        'stoptime',
        'is_agent',
        'kefu',
        'bank_name',
        'bank_acc_no',
        'bank_acc_name',
        'wallet',
        'money'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'paypassword',
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
        'money' => 'decimal:2',
        'rzstatus' => 'integer',
        'hy_result_mode' => 'integer',
        'level' => 'integer',
        'invit_1' => 'integer',
        'invit_2' => 'integer',
        'invit_3' => 'integer',
        'logins' => 'integer',
        'status' => 'integer',
        'txstate' => 'integer',
        'trade_locked' => 'integer',
        'is_agent' => 'integer',
        'kefu' => 'integer',
        'lgtime' => 'datetime',
        'logintime' => 'datetime',
        // 'cardzm' => ImageCast::class,
        // 'cardfm' => ImageCast::class,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Hash the password before saving.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = md5($password);
        }
    }

    public function setPaypasswordAttribute($paypassword)
    {
        if ($paypassword) {
            $this->attributes['paypassword'] = md5($paypassword);
        }
    }

    public function verifyPassword(string $plain): bool
    {
        $stored = (string) $this->password;
        $single = md5($plain);

        if (hash_equals($stored, $single)) {
            return true;
        }

        // Legacy: admin panel hashed twice (controller md5 + model mutator md5).
        return hash_equals($stored, md5($single));
    }

    public function verifyPaypassword(string $plain): bool
    {
        $stored = (string) $this->paypassword;
        $single = md5($plain);

        if (hash_equals($stored, $single)) {
            return true;
        }

        return hash_equals($stored, md5($single));
    }

    public function tradeLockMessage(): string
    {
        $message = trim((string) ($this->trade_lock_msg ?? ''));

        return $message !== '' ? $message : self::DEFAULT_TRADE_LOCK_MESSAGE;
    }

    public function syncPasswordFromPlain(string $plain): void
    {
        $hash = md5($plain);

        static::query()->whereKey($this->getKey())->update(['password' => $hash]);
        $this->attributes['password'] = $hash;
    }

    public function syncPaypasswordFromPlain(string $plain): void
    {
        $hash = md5($plain);

        static::query()->whereKey($this->getKey())->update(['paypassword' => $hash]);
        $this->attributes['paypassword'] = $hash;
    }

    public function repairPasswordIfLegacy(string $plain): void
    {
        if (!$this->verifyPassword($plain)) {
            return;
        }

        $singleHash = md5($plain);
        if (!hash_equals((string) $this->password, $singleHash)) {
            $this->syncPasswordFromPlain($plain);
        }
    }

    public function repairPaypasswordIfLegacy(string $plain): void
    {
        if (!$this->verifyPaypassword($plain)) {
            return;
        }

        $singleHash = md5($plain);
        if (!hash_equals((string) $this->paypassword, $singleHash)) {
            $this->syncPaypasswordFromPlain($plain);
        }
    }
}