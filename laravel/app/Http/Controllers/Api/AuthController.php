<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\Config;
use App\Models\User;
use App\Models\UserCoin;
use App\Models\UserLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
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
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:tw_user,username',
                function ($attribute, $value, $fail) {
                    // Kiểm tra là email hợp lệ HOẶC số điện thoại (bắt đầu bằng 0, đúng 10 số)
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && 
                        !preg_match('/^0\d{9}$/', $value)) {
                        $fail('Email phải là địa chỉ email hợp lệ hoặc số điện thoại 10 số bắt đầu bằng 0.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
            'paypassword' => 'required|string|min:6',
            // 'verification_code' => 'nullable|string|size:6',
            'invit' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng điền đầy đủ thông tin hợp lệ.",
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $email = strtolower(trim($request->email));
            $cacheKey = 'email_verify_code:' . sha1($email);
            $invitCode = trim($request->invit ?? '');

            $skipVerification = true; // Mặc định bỏ qua verification

            if ($invitCode && $invitCode !== '999999' && $invitCode !== '0') {
                $inv_user = User::where('invit', $invitCode)->exists();
                if ($inv_user) {
                    $skipVerification = true;        // invit hợp lệ → bỏ qua verification
                }
            }

            // Nếu không bỏ qua thì mới check verification_code
            if (!$skipVerification) {
                if (!$request->verification_code) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vui lòng nhập mã xác minh',
                    ], 422);
                }

                $verificationData = Cache::get($cacheKey);

                if (!$verificationData || !isset($verificationData['code']) || 
                    $verificationData['code'] !== trim($request->verification_code)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mã xác minh không hợp lệ hoặc đã hết hạn',
                    ], 422);
                }
            }

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
            $user = DB::transaction(function () use ($request, $email, $tymoney, $myinvit, $invit_1, $invit_2, $invit_3, $path, $ip, $city) {
                // Create user
                $user = User::create([
                    'username' => $email,
                    'password' => $request->password,
                    'paypassword' => $request->paypassword,
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
            Cache::forget($cacheKey);

            return response()->json([
                'status' => true,
                'message' => 'Đăng ký thành công',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
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
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Kiểm tra là email hợp lệ HOẶC số điện thoại (bắt đầu bằng 0, đúng 10 số)
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && 
                        !preg_match('/^0\d{9}$/', $value)) {
                        $fail('Phải là địa chỉ email hợp lệ hoặc số điện thoại 10 số bắt đầu bằng 0.');
                    }
                },
            ],
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng điền đầy đủ thông tin hợp lệ.",
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Find user by username
            $user = User::where('username', $request->email)->first();

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
            $user->update([
                'lgtime' => now()->toDateString(),
                'loginip' => $ip,
                'loginaddr' => $city,
                'logintime' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
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

    public function changePayPassword(Request $request)
    {
        try {
            // Validate request based on wdstatus
            $rules = [
                'paypassword' => 'required|string|min:6|max:32',
                'confirm_paypassword' => 'required|string|same:paypassword',
            ];

            $user = JWTAuth::user();

            if ($user->wdstatus == 1) {
                // User has paypassword, require current_paypassword
                $rules['current_paypassword'] = 'required|string';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Verify current paypassword if wdstatus = 1
            if ($user->wdstatus == 1 && !$user->verifyPaypassword($request->current_paypassword)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mật khẩu thanh toán không đúng',
                ], 422);
            }

            if ($user->wdstatus == 1) {
                $user->repairPaypasswordIfLegacy($request->current_paypassword);
            }

            // Update paypassword and wdstatus
            $updated = $user->update([
                'paypassword' => $request->paypassword,
                'wdstatus' => 1,
            ]);

            if ($updated) {
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

    public function sendVerificationCode(Request $request)
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

            $cooldownKey = 'email_verify_cooldown:' . sha1($email);
            if (Cache::has($cooldownKey)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng đợi 120 giây trước khi yêu cầu mã khác',
                ], 429);
            }

            $ipLimiterKey = 'email_verify:ip:' . $request->ip();
            $emailLimiterKey = 'email_verify:email:' . sha1($email);

            if (RateLimiter::tooManyAttempts($ipLimiterKey, 10) || RateLimiter::tooManyAttempts($emailLimiterKey, 5)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Quá nhiều yêu cầu, vui lòng thử lại sau',
                ], 429);
            }

            RateLimiter::hit($ipLimiterKey, 3600);
            RateLimiter::hit($emailLimiterKey, 3600);

            $code = (string) random_int(100000, 999999);
            $ttlSeconds = 300;

            Cache::put('email_verify_code:' . sha1($email), [
                'code' => $code,
                'email' => $email,
                'sent_at' => now()->timestamp,
            ], now()->addSeconds($ttlSeconds));

            Cache::put($cooldownKey, true, now()->addSeconds(120));

            Mail::raw("Your verification code is: {$code}. This code will expire in 5 minutes.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your verification code');
            });

            return response()->json([
                'status' => true,
                'message' => 'Mã xác minh đã được gửi thành công',
                'data' => [
                    'email' => $email,
                    'expires_in' => $ttlSeconds,
                ],
            ], 200);
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