<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tw_issue_log';

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
        'pid',
        'uid',
        'account',
        'name',
        'num',
        'open',
        'percent',
        'addtime',
        'endtime',
        'endday',
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
        'pid' => 'integer',
        'uid' => 'integer',
        'num' => 'decimal:2',
        'open' => 'integer',
        'percent' => 'decimal:2',
        'status' => 'integer',
        'addtime' => 'datetime',
        'endtime' => 'datetime',
        'endday' => 'date',
    ];

    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * Get the issue that the log belongs to.
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class, 'pid');
    }
}