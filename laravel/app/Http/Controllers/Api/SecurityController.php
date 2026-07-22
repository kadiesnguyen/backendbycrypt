<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\User;
use App\Services\EmailOtpService;
use App\Support\Totp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SecurityController extends Controller
{
    public function status()
    {
        /** @var User $user */
        $user = JWTAuth::user();

        return response()->json([
            'status' => true,
            'data' => $this->securityPayload($user),
        ]);
    }

    public function googleSetup(Request $request)
    {
        /** @var User $user */
        $user = JWTAuth::user();

        if ((int) $user->google2fa_enabled === 1) {
            return response()->json([
                'status' => true,
                'message' => 'Google Authenticator đã được bật',
                'data' => [
                    'enabled' => true,
                    'secret' => null,
                    'otpauth_url' => null,
                ],
            ]);
        }

        $secret = Totp::generateSecret();
        $user->google2fa_secret = $secret;
        $user->google2fa_enabled = 0;
        $user->save();

        $issuer = $this->issuerName();
        $account = $this->accountLabel($user);

        return response()->json([
            'status' => true,
            'message' => 'Tạo khóa Google Authenticator thành công',
            'data' => [
                'enabled' => false,
                'secret' => $secret,
                'otpauth_url' => Totp::otpauthUri($secret, $account, $issuer),
                'issuer' => $issuer,
                'account' => $account,
            ],
        ]);
    }

    public function googleEnable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        /** @var User $user */
        $user = JWTAuth::user();

        if (empty($user->google2fa_secret)) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng tạo khóa Google Authenticator trước',
            ], 422);
        }

        if (!Totp::verify((string) $user->google2fa_secret, (string) $request->code)) {
            return response()->json([
                'status' => false,
                'message' => 'Mã Google Authenticator không hợp lệ',
            ], 422);
        }

        $user->google2fa_enabled = 1;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Bật Google Authenticator thành công',
            'data' => $this->securityPayload($user),
        ]);
    }

    public function emailSendCode(Request $request, EmailOtpService $otp)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        /** @var User $user */
        $user = JWTAuth::user();
        $email = strtolower(trim((string) $request->email));

        $taken = User::where(function ($q) use ($email) {
            $q->where('username', $email)
                ->orWhere('security_email', $email);
        })
            ->where('id', '!=', $user->id)
            ->exists();

        if ($taken) {
            return response()->json([
                'status' => false,
                'message' => 'Email đã được sử dụng bởi tài khoản khác',
            ], 422);
        }

        try {
            $result = $otp->send($email, EmailOtpService::PURPOSE_EMAIL_BIND, (string) $request->ip());

            return response()->json([
                'status' => true,
                'message' => 'Mã xác minh đã được gửi thành công',
                'data' => [
                    'email' => $email,
                    'expires_in' => $result['expires_in'],
                ],
            ]);
        } catch (RuntimeException $e) {
            $status = str_contains($e->getMessage(), 'đợi') || str_contains($e->getMessage(), 'Quá nhiều')
                ? 429
                : 422;

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $status);
        } catch (Exception $e) {
            Log::error('Security email send failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi gửi mã xác minh, vui lòng thử lại sau',
            ], 500);
        }
    }

    public function emailVerify(Request $request, EmailOtpService $otp)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        /** @var User $user */
        $user = JWTAuth::user();
        $email = strtolower(trim((string) $request->email));

        if (!$otp->verify($email, (string) $request->code, EmailOtpService::PURPOSE_EMAIL_BIND)) {
            return response()->json([
                'status' => false,
                'message' => 'Mã xác minh không hợp lệ hoặc đã hết hạn',
            ], 422);
        }

        $taken = User::where(function ($q) use ($email) {
            $q->where('username', $email)
                ->orWhere('security_email', $email);
        })
            ->where('id', '!=', $user->id)
            ->exists();

        if ($taken) {
            return response()->json([
                'status' => false,
                'message' => 'Email đã được sử dụng bởi tài khoản khác',
            ], 422);
        }

        $user->security_email = $email;
        $user->security_email_verified = 1;
        $user->save();
        $otp->consume($email, EmailOtpService::PURPOSE_EMAIL_BIND);

        return response()->json([
            'status' => true,
            'message' => 'Xác minh email thành công',
            'data' => $this->securityPayload($user),
        ]);
    }

    private function securityPayload(User $user): array
    {
        $username = (string) $user->username;
        $usernameIsEmail = filter_var($username, FILTER_VALIDATE_EMAIL) !== false;

        return [
            'google2fa_enabled' => (int) $user->google2fa_enabled === 1,
            'security_email' => $user->security_email,
            'security_email_verified' => (int) $user->security_email_verified === 1,
            'default_email' => $user->security_email
                ?: ($usernameIsEmail ? $username : null),
        ];
    }

    private function issuerName(): string
    {
        $config = Config::first();
        $name = trim((string) ($config->webname ?? ''));

        return $name !== '' ? $name : 'Bycrypt';
    }

    private function accountLabel(User $user): string
    {
        if (!empty($user->security_email)) {
            return (string) $user->security_email;
        }

        $username = (string) $user->username;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return $username;
        }

        return 'user'.$user->id;
    }
}
