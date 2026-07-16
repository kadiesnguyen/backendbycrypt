<?php

namespace App\Support;

use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class NotificationTtl
{
    public const TTL_HOURS = 24;

    public static function expiresBefore(): Carbon
    {
        return now()->subHours(self::TTL_HOURS);
    }

    public static function purgeExpiredNotices(): int
    {
        return Notice::query()
            ->where('addtime', '<', self::expiresBefore())
            ->delete();
    }

    /**
     * @param  Builder<Notice>  $query
     * @return Builder<Notice>
     */
    public static function scopeActiveNotices(Builder $query): Builder
    {
        return $query->where('addtime', '>=', self::expiresBefore());
    }
}
