<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQianbao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_user_qianbao';

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
        'coinname',
        'name',
        'remark',
        'czline',
        'addr',
        'sort',
        'addtime',
        'status'
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
        'sort' => 'integer',
        'addtime' => 'datetime',
        'status' => 'integer',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}