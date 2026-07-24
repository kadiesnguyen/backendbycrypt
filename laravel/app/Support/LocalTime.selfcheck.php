<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Support\LocalTime;

assert(LocalTime::timezoneForLocale('vi') === 'Asia/Ho_Chi_Minh');
assert(LocalTime::timezoneForLocale(null) === 'Asia/Ho_Chi_Minh');
assert(LocalTime::timezoneForLocale('en') === 'UTC');
assert(LocalTime::gmtLabel('vi') === 'GMT+7');
assert(str_contains(LocalTime::formatNow('vi'), '(GMT+7)'));

echo "LocalTime.selfcheck: ok\n";
