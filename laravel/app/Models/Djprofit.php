<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Djprofit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_djprofit';

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
        'num',
        'coin',
        'status',
        'addtime',
        'addday',
        'thawtime',
        'thawday',
        'remark'
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
        'num' => 'decimal:2',
        'status' => 'integer',
        'addtime' => 'datetime',
        'addday' => 'date',
        'thawtime' => 'datetime',
        'thawday' => 'date',
    ];

    /**
     * Get the user that owns the profit.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}