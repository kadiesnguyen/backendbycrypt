<?php

namespace App\Console\Commands;

use App\Services\EmailOtpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class EmailOtpSelfCheck extends Command
{
    protected $signature = 'app:email-otp-self-check';

    protected $description = 'Assert email OTP cache verify/consume helpers.';

    public function handle(EmailOtpService $otp): int
    {
        $email = 'otp-selfcheck@bycrypt.test';
        $purpose = EmailOtpService::PURPOSE_PAYPASSWORD;
        $code = '123456';

        $ref = new ReflectionClass($otp);
        $codeKey = $ref->getMethod('codeKey');
        $codeKey->setAccessible(true);
        $key = $codeKey->invoke($otp, $email, $purpose);

        Cache::put($key, [
            'code' => $code,
            'email' => $email,
            'sent_at' => time(),
        ], now()->addMinutes(5));

        if (!$otp->verify($email, $code, $purpose)) {
            $this->error('verify expected true');

            return 1;
        }

        if ($otp->verify($email, '000000', $purpose)) {
            $this->error('verify expected false for wrong code');

            return 1;
        }

        $otp->consume($email, $purpose);
        if ($otp->verify($email, $code, $purpose)) {
            $this->error('verify expected false after consume');

            return 1;
        }

        $this->info('Email OTP self-check passed.');

        return 0;
    }
}
