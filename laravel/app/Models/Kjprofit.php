<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kjprofit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_kjprofit';

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
        'username',
        'kid',
        'ktitle',
        'num',
        'coin',
        'addtime',
        'day'
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
        'kid' => 'integer',
        'num' => 'decimal:2',
        'addtime' => 'datetime',
        'day' => 'date',
    ];

    /**
     * Get the user that owns the profit.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * Get the kjorder that the profit belongs to.
     */
    public function kjorder()
    {
        return $this->belongsTo(Kjorder::class, 'kid');
    }
}