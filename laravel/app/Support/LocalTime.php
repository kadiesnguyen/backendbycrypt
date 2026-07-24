<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Format wall-clock times for notices using the user's UI locale timezone.
 * App DB naive datetimes are written in Asia/Ho_Chi_Minh; labels must not say UTC.
 */
final class LocalTime
{
    public static function timezoneForLocale(?string $locale): string
    {
        $locale = strtolower(trim((string) $locale));

        return match ($locale) {
            'en' => 'UTC',
            'po', 'pt' => 'America/Sao_Paulo',
            'gr' => 'Europe/Athens',
            default => 'Asia/Ho_Chi_Minh',
        };
    }

    public static function gmtLabel(?string $locale = null): string
    {
        $hours = (int) round(Carbon::now(self::timezoneForLocale($locale))->utcOffset() / 60);
        $sign = $hours >= 0 ? '+' : '';

        return 'GMT' . $sign . $hours;
    }

    public static function formatNow(?string $locale = null): string
    {
        $tz = self::timezoneForLocale($locale);

        return Carbon::now($tz)->format('Y-m-d H:i:s') . ' (' . self::gmtLabel($locale) . ')';
    }
}
