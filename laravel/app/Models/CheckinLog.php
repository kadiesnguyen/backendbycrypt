<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    protected $table = 'tw_checkin_log';
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'username',
        'streak',
        'reward',
        'checkin_date',
        'addtime',
    ];

    protected $casts = [
        'reward' => 'decimal:2',
        'checkin_date' => 'date:Y-m-d',
        'addtime' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}