<?php

// Run: php app/Support/Totp.selfcheck.php (from laravel/)
require dirname(__DIR__, 2).'/vendor/autoload.php';

use App\Support\Totp;

$secret = Totp::generateSecret();
$code = Totp::codeAt($secret, (int) floor(time() / 30));
assert(Totp::verify($secret, $code) === true, 'current TOTP must verify');
assert(Totp::verify($secret, '000000') === false, 'wrong code must fail');
$uri = Totp::otpauthUri($secret, 'user@example.com', 'Bycrypt');
assert(str_starts_with($uri, 'otpauth://totp/'), 'otpauth uri prefix');

echo "Totp.selfcheck OK\n";
