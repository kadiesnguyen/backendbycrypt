<?php
/**
 * ponytail: runnable check — php backend/laravel/app/Services/PerpAdminWinCalc.selfcheck.php
 */
declare(strict_types=1);

$margin = 100.0;
$rate = 80.0;
$profit = $margin * ($rate / 100);
$credit = $margin + $profit;

assert(abs($profit - 80.0) < 1e-9, 'profit');
assert(abs($credit - 180.0) < 1e-9, 'credit');

echo "PerpAdminWinCalc.selfcheck: ok\n";
