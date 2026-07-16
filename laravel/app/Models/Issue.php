<?php

namespace App\Models;

use App\Casts\ConfigImageCast;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_issue';

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
        'name',
        'min',
        'max',
        'open',
        'percent',
        'imgs',
        'content',
        'addtime',
        'status',
        'state'
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
        'min' => 'decimal:0',
        'max' => 'decimal:0',
        'open' => 'integer',
        'percent' => 'float',
        'imgs' => ConfigImageCast::class,
        'addtime' => 'datetime',
        'status' => 'integer',
        'state' => 'integer',
    ];

    /**
     * Get the logs for the issue.
     */
    public function logs()
    {
        return $this->hasMany(IssueLog::class, 'pid');
    }
}