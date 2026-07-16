<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'tw_news';

    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'title',
        'coverImage',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
