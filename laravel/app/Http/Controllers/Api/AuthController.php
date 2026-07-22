<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\Config;
use App\Models\User;
use App\Models\UserCoin;
use App\Models\UserLog;
use App\Services\EmailOtpService;
use App\Support\LocalePhoneCatalog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Stevebauman\Location\Facades\Location;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $phoneCode = $request->input('phone_code');
        $uiLocale = LocalePhoneCatalog::resolveUiLocale(
            $request->input('locale'),
            $phoneCode ? (string) $phoneCode : null
        );

        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($phoneCode) {
                    if (!LocalePhoneCatalog::isValidLoginIdentifier((string) $value, $phoneCode ? (string) $phoneCode : null)) {
                        $fail('Email phải là địa chỉ email hợp lệ hoặc số điện thoại hợp lệ.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
            'paypassword' => 'nullable|string|min:6',
            'Repassword' => 'nullable|string|same:password',
            'verification_code' => 'nullable|string|size:6',
            'invit' => 'nullable|string|size:6',
            'phone_code' => 'nullable|string|max:4',
            'locale' => 'nullable|string|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng điền đầy đủ thông tin hợp lệ.",
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            try {
                $email = LocalePhoneCatalog::normalizeUsername(
                    (string) $request->email,
                    $phoneCode ? (string) $phoneCode : null
                );
            } catch (\InvalidArgumentException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email hoặc số điện thoại không hợp lệ.',
                ], 422);
            }

            if (User::where('username', $email)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email hoặc số điện thoại đã được sử dụng.',
                ], 422);
            }

            $invitCode = trim($request->invit ?? '');
            if ($invitCode === '') {
                $invitCode = '999999';
            }
            $otp = app(EmailOtpService::class);
            $rawIdentifier = trim((string) $request->email);
            $isEmailSignup = filter_var($rawIdentifier, FILTER_VALIDATE_EMAIL) !== false;

            $hasValidReferral = false;
            if ($invitCode !== '999999' && $invitCode !== '0') {
                $hasValidReferral = User::where('invit', $invitCode)->exists();
            }

            if ($isEmailSignup && !$hasValidReferral) {
                if (!$request->verification_code) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vui lòng nhập mã xác minh email',
                    ], 422);
                }

                if (!$otp->verify($email, (string) $request->verification_code, EmailOtpService::PURPOSE_REGISTER)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mã xác minh không hợp lệ hoặc đã hết hạn',
                    ], 422);
                }

                $otp->consume($email, EmailOtpService::PURPOSE_REGISTER);
            }

            $paypassword = $request->filled('paypassword')
                ? (string) $request->paypassword
                : (string) $request->password;

            // ====================== XỬ LÝ REFERRAL ======================
            $invit_1 = 0;
            $invit_2 = 0;
            $invit_3 = 0;
            $path = '';

            if ($invitCode && $invitCode !== '999999' && $invitCode !== '0') {
                $inv_user = User::where('invit', $invitCode)
                                ->select('id', 'username', 'invit_1', 'invit_2', 'path')
                                ->first();

                if ($inv_user) {
                    $invit_1 = $inv_user->id;
                    $invit_2 = $inv_user->invit_1;
                    $invit_3 = $inv_user->invit_2;
                    $path = $inv_user->path . ',' . $inv_user->id;
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Người giới thiệu không tồn tại',
                    ], 422);
                }
            }

            // Get config
            $config = Config::find(1);
            $tymoney = $config ? $config->tymoney : 0.00;

            // Generate unique invite code
            $myinvit = $this->generateUniqueInviteCode();

            // Get IP and location
            $ip = $request->ip();
            $city = 'Unknown';
            try {
                $location = Location::get($ip);
                $city = $location ? ($location->cityName ?? 'Unknown') : 'Unknown';
            } catch (Exception $e) {
                Log::warning('Failed to get location for IP: ' . $ip, ['error' => $e->getMessage()]);
            }

            // Start database transaction
            $user = DB::transaction(function () use ($request, $email, $tymoney, $myinvit, $invit_1, $invit_2, $invit_3, $path, $ip, $city, $paypassword, $uiLocale) {
                // Create user
                $user = User::create([
                    'username' => $email,
                    'ui_locale' => $uiLocale,
                    'password' => $request->password,
                    'paypassword' => $paypassword,
                    'money' => $tymoney,
                    'invit' => $myinvit,
                    'invit_1' => $invit_1,
                    'invit_2' => $invit_2,
                    'invit_3' => $invit_3,
                    'path' => $path,
                    'addip' => $ip,
                    'addr' => "Viet Nam",
                    'loginaddr' => $city,
                    'loginip' => $ip,
                    'addtime' => now()->timestamp,
                    'status' => 1,
                    'txstate' => 1,
                    'rzstatus' => 0,
                    'lgtime' => now()->toDateString(),
                    'logintime' => now()->toDateTimeString(),
                    'rztime' => now()->timestamp,
                    'rzuptime' => now()->timestamp,
                    'stoptime' => 0,
                    'cardzm' => '',
                    'cardfm' => '',
                    'kefu' => '0',
                    'wdstatus' => 1,
                ]);

                // Create user coin
                UserCoin::create([
                    'userid' => $user->id,
                ]);

                // Log registration
                UserLog::create([
                    'userid' => $user->id,
                    'type' => 'Đăng ký',
                    'remark' => 'Đăng ký tài khoản mới',
                    'addtime' => now()->timestamp,
                    'addip' => $ip,
                    'addr' => $city,
                    'status' => 1,
                ]);

                return $user;
            });

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => true,
                'message' => 'Đăng ký thành công',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'ui_locale' => $user->ui_locale ?? $uiLocale,
                ],
                'token' => $token,
            ], 201);
        } catch (Exception $e) {
            Log::error('Registration processing failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Đăng ký thất bại, vui lòng thử lại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user => return JWT token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $phoneCode = $request->input('phone_code');

        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($phoneCode) {
                    if (!LocalePhoneCatalog::isValidLoginIdentifier((string) $value, $phoneCode ? (string) $phoneCode : null)) {
                        $fail('Phải là địa chỉ email hợp lệ hoặc số điện thoại hợp lệ.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
            'phone_code' => 'nullable|string|max:4',
            'locale' => 'nullable|string|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng điền đầy đủ thông tin hợp lệ.",
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            try {
                $username = LocalePhoneCatalog::normalizeUsername(
                    (string) $request->email,
                    $phoneCode ? (string) $phoneCode : null
                );
            } catch (\InvalidArgumentException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email hoặc số điện thoại không hợp lệ.',
                ], 422);
            }

            // Find user by username
            $user = User::where('username', $username)->first();

            // Check if user exists and password matches
            if (!$user || !$user->verifyPassword($request->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy tài khoản hoặc mật khẩu không đúng',
                ], 401);
            }

            // Repair legacy double-hashed passwords from admin panel edits.
            $user->repairPasswordIfLegacy($request->password);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Check status
            if ($user->status != 1) {
                JWTAuth::setToken($token)->invalidate(true);
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ hỗ trợ',
                ], 403);
            }

            // Update login count
            $user->increment('logins');

            // Log login action
            $ip = $request->ip();
            $location = Location::get($ip);
            $city = $location ? ($location->cityName ?? 'Unknown') : 'Unknown';

            UserLog::create([
                'userid' => $user->id,
                'type' => 'Đăng nhập',
                'remark' => 'Đăng nhập bằng email',
                'addtime' => now()->timestamp,
                'addip' => $ip,
                'addr' => $city,
                'status' => 1,
            ]);

            // Update login message
            $loginLocale = LocalePhoneCatalog::resolveUiLocale(
                $request->input('locale'),
                $phoneCode ? (string) $phoneCode : null
            );
            if ($request->filled('locale') || $request->filled('phone_code')) {
                $user->ui_locale = $loginLocale;
            }

            $user->update([
                'lgtime' => now()->toDateString(),
                'loginip' => $ip,
                'loginaddr' => $city,
                'logintime' => now()->toDateTimeString(),
                'ui_locale' => $user->ui_locale,
            ]);

            $resolvedLocale = LocalePhoneCatalog::normalizeUiLocale($user->ui_locale);

            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'ui_locale' => $resolvedLocale,
                ],
                'token' => $token,
            ], 200);
        } catch (JWTException $e) {
            Log::error('JWT authentication failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            Log::error('Login processing failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = JWTAuth::user();


            // Get coin list
            $coins = Coin::where('status', 1)
                ->orderBy('sort', 'asc')
                ->get();

            // Get user coin balances
            $userCoin = UserCoin::where('userid', $user->id)->first();

            // Map coins to balance array
            $balance = [];
            foreach ($coins as $coin) {
                $column = $coin->name;
                $frozenColumn = $column . '_d';
                
                // Số dư thông thường
                $balance[$column] = number_format((float) ($userCoin->$column ?? 0.00), 2, '.', '');
                
                // Số dư bị đóng băng
                $balance[$frozenColumn] = number_format((float) ($userCoin->$frozenColumn ?? 0.00), 2, '.', '');
                
                // Tổng số dư (thông thường + đóng băng)
                $total = (float) ($userCoin->$column ?? 0.00) + (float) ($userCoin->$frozenColumn ?? 0.00);
                $balance[$column . '_total'] = number_format($total, 2, '.', '');
            }
            
            // Convert user to array and add balance
            $userData = $user->toArray();
            $userData['balance'] = $balance;

            return response()->json([
                'status' => true,
                'data' => $userData,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xác thực token, vui lòng đăng nhập lại',
            ], 401);
        } catch (Exception $e) {
            \Log::error('User info retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy thông tin người dùng, vui lòng thử lại',
            ], 500);
        }
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => true,
                'message' => 'Đăng xuất thành công',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi đăng xuất, vui lòng thử lại',
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Check old password
            if (!$user->verifyPassword($request->old_password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mật khẩu cũ không đúng',
                ], 401);
            }

            // Update password
            $user->password = $request->new_password; // Will be hashed by setPasswordAttribute
            $user->save();

            // Log password change action
            $ip = $request->ip();
            $location = Location::get($ip);
            $city = $location->city ?? 'Unknown';

            UserLog::create([
                'userid' => $user->id,
                'type' => 'Đổi mật khẩu',
                'remark' => 'Đổi mật khẩu thành công',
                'addtime' => now()->timestamp,
                'addip' => $ip,
                'addr' => $city,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Đổi mật khẩu thành công',
            ], 200);
        } catch (Exception $e) {
            \Log::error('Password change failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Đổi mật khẩu thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function changePayPassword(Request $request, EmailOtpService $otp)
    {
        try {
            $user = JWTAuth::user();

            $validator = Validator::make($request->all(), [
                'paypassword' => ['required', 'string', 'regex:/^\d{6}$/'],
                'confirm_paypassword' => 'required|string|same:paypassword',
                'verification_code' => 'required|string|size:6',
                'verify_type' => 'required|string|in:email,google',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            if ($request->verify_type === 'google') {
                return response()->json([
                    'status' => false,
                    'message' => 'Xác minh Google chưa được hỗ trợ',
                ], 422);
            }

            $email = strtolower(trim((string) $user->username));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản chưa có email hợp lệ để xác minh',
                ], 422);
            }

            if (!$otp->verify($email, (string) $request->verification_code, EmailOtpService::PURPOSE_PAYPASSWORD)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã xác minh không hợp lệ hoặc đã hết hạn',
                ], 422);
            }

            $updated = $user->update([
                'paypassword' => $request->paypassword,
                'wdstatus' => 1,
            ]);

            if ($updated) {
                $otp->consume($email, EmailOtpService::PURPOSE_PAYPASSWORD);

                return response()->json([
                    'status' => true,
                    'message' => 'Cập nhật mật khẩu thanh toán thành công',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Cập nhật mật khẩu thanh toán thất bại. Vui lòng thử lại sau.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Pay password update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật mật khẩu thanh toán thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function sendPaypasswordCode(Request $request, EmailOtpService $otp)
    {
        try {
            $user = JWTAuth::user();
            $email = strtolower(trim((string) $user->username));

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản chưa có email hợp lệ để gửi mã',
                ], 422);
            }

            $result = $otp->send($email, EmailOtpService::PURPOSE_PAYPASSWORD, (string) $request->ip());

            return response()->json([
                'status' => true,
                'message' => 'Mã xác minh đã được gửi thành công',
                'data' => [
                    'email' => $email,
                    'expires_in' => $result['expires_in'],
                ],
            ], 200);
        } catch (RuntimeException $e) {
            $status = str_contains($e->getMessage(), 'đợi') || str_contains($e->getMessage(), 'Quá nhiều')
                ? 429
                : 422;

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $status);
        } catch (Exception $e) {
            Log::error('Send paypassword verification code failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi gửi mã xác minh, vui lòng thử lại sau',
            ], 500);
        }
    }

    public function sendVerificationCode(Request $request, EmailOtpService $otp)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:tw_user,username',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $email = strtolower(trim($request->email));
            $result = $otp->send($email, EmailOtpService::PURPOSE_REGISTER, (string) $request->ip());

            return response()->json([
                'status' => true,
                'message' => 'Mã xác minh đã được gửi thành công',
                'data' => [
                    'email' => $email,
                    'expires_in' => $result['expires_in'],
                ],
            ], 200);
        } catch (RuntimeException $e) {
            $status = str_contains($e->getMessage(), 'đợi') || str_contains($e->getMessage(), 'Quá nhiều')
                ? 429
                : 422;

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $status);
        } catch (Exception $e) {
            Log::error('Send verification code failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi gửi mã xác minh, vui lòng thử lại sau',
            ], 500);
        }
    }

    protected function generateUniqueInviteCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (User::where('invit', $code)->exists());

        return $code;
    }
}