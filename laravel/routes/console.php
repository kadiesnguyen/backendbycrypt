<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:process-orders')->everyFifteenSeconds()->withoutOverlapping();

Schedule::command('app:process-staking')->everyThirtyMinutes()->withoutOverlapping();

Schedule::command('app:reset-checkin-streak')->dailyAt('00:05')->withoutOverlapping();

Schedule::command('app:earning-daily-kuangji')->everyFiveMinutes()->withoutOverlapping();

Schedule::command('app:process-limit-trade-orders')->everyFifteenSeconds()->withoutOverlapping();

Schedule::command('app:purge-expired-notices')->hourly()->withoutOverlapping();