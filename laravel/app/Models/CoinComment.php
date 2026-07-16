<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_coin_comment';

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
        'content',
        'cjz',
        'tzy',
        'xcd',
        'sort',
        'addtime',
        'endtime',
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
        'cjz' => 'integer',
        'tzy' => 'integer',
        'xcd' => 'integer',
        'sort' => 'integer',
        'addtime' => 'integer',
        'endtime' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    /**
     * Get the coin that the comment belongs to.
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coinname', 'name');
    }
}