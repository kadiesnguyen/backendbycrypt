<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class EmailOtpService
{
    public const PURPOSE_REGISTER = 'register';

    public const PURPOSE_PAYPASSWORD = 'paypassword';

    public const PURPOSE_EMAIL_BIND = 'email_bind';

    private const TTL_SECONDS = 300;

    private const COOLDOWN_SECONDS = 120;

    /**
     * @return array{code: string, expires_in: int}
     */
    public function send(string $email, string $purpose, string $ip): array
    {
        $email = strtolower(trim($email));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Email không hợp lệ');
        }

        $cooldownKey = $this->cooldownKey($email, $purpose);
        if (Cache::has($cooldownKey)) {
            throw new RuntimeException('Vui lòng đợi 120 giây trước khi yêu cầu mã khác');
        }

        $ipLimiterKey = "email_otp:ip:{$purpose}:" . $ip;
        $emailLimiterKey = "email_otp:email:{$purpose}:" . sha1($email);

        if (RateLimiter::tooManyAttempts($ipLimiterKey, 10) || RateLimiter::tooManyAttempts($emailLimiterKey, 5)) {
            throw new RuntimeException('Quá nhiều yêu cầu, vui lòng thử lại sau');
        }

        RateLimiter::hit($ipLimiterKey, 3600);
        RateLimiter::hit($emailLimiterKey, 3600);

        $code = (string) random_int(100000, 999999);

        Cache::put($this->codeKey($email, $purpose), [
            'code' => $code,
            'email' => $email,
            'sent_at' => now()->timestamp,
        ], now()->addSeconds(self::TTL_SECONDS));

        Cache::put($cooldownKey, true, now()->addSeconds(self::COOLDOWN_SECONDS));

        $fromName = config('mail.from.name') ?: 'Bycrypt';
        $subject = config('mail.otp_subject', 'Mã xác thực Bycrypt');

        Mail::raw(
            "Mã xác thực Bycrypt của bạn là: {$code}. Mã hết hạn sau 5 phút.",
            function ($message) use ($email, $subject, $fromName) {
                $message->to($email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), $fromName);
            }
        );

        return [
            'code' => $code,
            'expires_in' => self::TTL_SECONDS,
        ];
    }

    public function verify(string $email, string $code, string $purpose): bool
    {
        $email = strtolower(trim($email));
        $code = trim($code);
        $cached = Cache::get($this->codeKey($email, $purpose));

        if (!$cached || !isset($cached['code'])) {
            return false;
        }

        return hash_equals((string) $cached['code'], $code);
    }

    public function consume(string $email, string $purpose): void
    {
        $email = strtolower(trim($email));
        Cache::forget($this->codeKey($email, $purpose));
        Cache::forget($this->cooldownKey($email, $purpose));
    }

    private function codeKey(string $email, string $purpose): string
    {
        return 'email_otp_code:' . $purpose . ':' . sha1($email);
    }

    private function cooldownKey(string $email, string $purpose): string
    {
        return 'email_otp_cooldown:' . $purpose . ':' . sha1($email);
    }
}
