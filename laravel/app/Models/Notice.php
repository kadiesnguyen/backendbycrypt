<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_notice';

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
        'account',
        'title',
        'content',
        'imgs',
        'addtime',
        'status',
        'user_view',
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
        'account' => 'string',
        'addtime' => 'datetime',
        'status' => 'integer',
    ];

    /**
     * Get the user that owns the notice.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}