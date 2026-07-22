<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Recharge;
use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UserLog;
use App\Models\Notice;
use App\Support\LocalePhoneCatalog;
use App\Support\NotificationTtl;

class UserController extends Controller
{
    /**
     * Verification center summary for client VerificationCenterPage.
     */
    public function verificationStatus()
    {
        try {
            $user = JWTAuth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $rzstatus = (int) ($user->rzstatus ?? 0);
            $fullname = trim((string) ($user->fullname ?? ''));
            $cccd = trim((string) ($user->cccd ?? ''));
            $cardfm = trim((string) ($user->cardfm ?? ''));
            $cardzm = trim((string) ($user->cardzm ?? ''));
            $hasIdImages = $cardfm !== '' && $cardzm !== '';
            $personalInfoDone = $fullname !== '';
            $governmentIdDone = $cccd !== '' && $hasIdImages;

            $statusMap = [
                0 => 'none',
                1 => 'pending',
                2 => 'approved',
                3 => 'rejected',
            ];
            $statusLabel = $statusMap[$rzstatus] ?? 'none';

            $locale = LocalePhoneCatalog::normalizeUiLocale($user->ui_locale ?? 'vi');
            $localeEntry = LocalePhoneCatalog::findByLocale($locale)
                ?? LocalePhoneCatalog::findByLocale('vi');

            $flagUrl = $localeEntry['flag_url'] ?? 'https://flagcdn.com/vn.svg';
            // Compact PNG for small flag badge on center page.
            $flagCode = match ($locale) {
                'en' => 'us',
                'po' => 'pt',
                'gr' => 'gr',
                default => $locale,
            };

            return response()->json([
                'status' => true,
                'message' => 'Verification status retrieved successfully.',
                'data' => [
                    'fullname' => $fullname !== '' ? $fullname : null,
                    'cccd' => $cccd !== '' ? $cccd : null,
                    'rzstatus' => $rzstatus,
                    'rzstatus_label' => $statusLabel,
                    'primary_certified' => $rzstatus === 2,
                    'advanced_certified' => false,
                    'personal_info_done' => $personalInfoDone,
                    'government_id_done' => $governmentIdDone,
                    'has_id_images' => $hasIdImages,
                    'cardfm' => $cardfm !== '' ? $cardfm : null,
                    'cardzm' => $cardzm !== '' ? $cardzm : null,
                    'review_days' => 3,
                    'submitted_at' => !empty($user->rztime) ? date('c', (int) $user->rztime) : null,
                    'reviewed_at' => !empty($user->rzuptime) && $rzstatus >= 2
                        ? date('c', (int) $user->rzuptime)
                        : null,
                    'can_submit' => !in_array($rzstatus, [1, 2], true),
                    'locale' => $locale,
                    'country_name' => $localeEntry['name'] ?? 'Tiếng Việt',
                    'flag_url' => $flagUrl,
                    'flag_png' => "https://flagcdn.com/w80/{$flagCode}.png",
                    'detail_path' => '/verified',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Verification status failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to load verification status.',
            ], 500);
        }
    }

    public function verifyAccount(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'cccd' => 'required|string',
            'fullname' => 'required|string',
            'cardzm' => 'required|image|max:16384',
            'cardfm' => 'required|image|max:16384',
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

            // Check if cccd number is already used
            if (User::where('cccd', $request->cccd)->where('id', '!=', $user->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your ID number is already in use by another account.',
                ], 422);
            }

            // Check if already verified
            if ($user->rzstatus == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot verify account, it is sent.',
                ], 422);
            }
            if ($user->rzstatus == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot verify account, it is already verified.',
                ], 422);
            }

            // Upload images
            $cardzmPath = $request->file('cardzm')->store('verifications', 'public');
            $cardfmPath = $request->file('cardfm')->store('verifications', 'public');

            // Generate full URLs
            $cardzmFullUrl = asset('storage/' . $cardzmPath);
            $cardfmFullUrl = asset('storage/' . $cardfmPath);

            // Prepare data for update
            $data = [
                'cccd' => $request->cccd,
                'fullname' => strtoupper($request->fullname),
                'cardzm' => $cardzmFullUrl,
                'cardfm' => $cardfmFullUrl,
                'rzstatus' => 1,
                'rztime' => now()->timestamp,
            ];

            // Update user
            $updated = $user->update($data);

            if ($updated) {
                // Create notice
                Notice::create([
                    'uid' => $user->id,
                    'account' => $user->username,
                    'title' => 'Verification Information Submitted',
                    'content' => 'Have submitted verification information. Please wait for admin approval.',
                    'addtime' => now()->toDateTimeString(),
                    'status' => 1,
                ]);

                // Log verification action
                $ip = $request->ip();
                $location = Location::get($ip);
                $city = $location->city ?? 'Unknown';

                UserLog::create([
                    'userid' => $user->id,
                    'type' => 'Xác minh tài khoản',
                    'remark' => 'Gửi thông tin xác minh thành công',
                    'addtime' => now()->timestamp,
                    'addip' => $ip,
                    'addr' => $city,
                    'status' => 1,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully submitted verification information. Please wait for admin approval.',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Sending verification information failed. Please try again later.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Account verification failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Sending verification information failed. Please try again later.'
            ], 500);
        }
    }

    public function bills(Request $request)
    {
        try {
            $user = JWTAuth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please log in to view your bills.',
                ], 401);
            }

            // Get bills
            $bills = Bill::where('uid', $user->id)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Bills retrieved successfully',
                'data' => $bills->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('My bill retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve bills. Try again later.',
            ], 500);
        }
    }

    public function notices(Request $request)
    {
        try {
            NotificationTtl::purgeExpiredNotices();

            // Get authenticated user
            $user = JWTAuth::user();

            // Get notices from the last 24 hours
            $notices = NotificationTtl::scopeActiveNotices(
                Notice::where('uid', $user->id)
            )
                ->orderBy('user_view')
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get();

            // Count unread notices
            $unreadCount = NotificationTtl::scopeActiveNotices(
                Notice::where('uid', $user->id)->where('user_view', 1)
            )->count();

            return response()->json([
                'status' => true,
                'message' => 'Notices retrieved successfully',
                'data' => [
                    'unread_count' => $unreadCount,
                    'notices' => $notices->toArray(),
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('My notice retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve notices. Try again later.',
            ], 500);
        }
    }

    public function getNoticeDetail(Request $request, $id)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get notice by ID and check if it belongs to the user
            $notice = NotificationTtl::scopeActiveNotices(
                Notice::where('id', $id)->where('uid', $user->id)
            )->first();

            if (!$notice) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notice not found or you do not have permission.',
                ], 404);
            }

            // Update user_view to "read" (2) if currently "unread" (1)
            if ($notice->user_view == 1) {
                $notice->update(['user_view' => 2]);
            }

            // Format response
            return response()->json([
                'status' => true,
                'message' => 'Notice retrieved successfully',
                'data' => $notice->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Notice detail retrieval failed', [
                'user_id' => auth()->id(),
                'notice_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve notice. Try again later.',
            ], 500);
        }
    }

    public function markAllNoticesRead(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Update all unread notices to read
            $updatedCount = Notice::where('uid', $user->id)
                ->where('user_view', 1)
                ->update(['user_view' => 2]);

            // Log the action
            if ($updatedCount > 0) {
                \Log::info('All notices marked as read', [
                    'user_id' => $user->id,
                    'updated_count' => $updatedCount,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => $updatedCount > 0 ? 'All notices marked as read successfully' : 'No unread notices to mark as read',
                'data' => [
                    'updated_count' => $updatedCount,
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Mark all notices as read failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notices as read. Try again later.',
            ], 500);
        }
    }

    public function referral(Request $request)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please log in to view your referral info.',
                ], 401);
            }

            $level = (int) $request->query('level', 1);
            if (!in_array($level, [1, 2, 3], true)) {
                $level = 1;
            }

            $levelField = match ($level) {
                1 => 'invit_1',
                2 => 'invit_2',
                3 => 'invit_3',
            };

            $levelOneCount = User::query()->where('invit_1', $user->id)->count();
            $levelTwoCount = User::query()->where('invit_2', $user->id)->count();
            $levelThreeCount = User::query()->where('invit_3', $user->id)->count();

            $totalBonus = (float) Bill::query()
                ->where('uid', $user->id)
                ->where('type', 9)
                ->sum('num');

            $downlineIds = User::query()
                ->where(function ($query) use ($user) {
                    $query->where('invit_1', $user->id)
                        ->orWhere('invit_2', $user->id)
                        ->orWhere('invit_3', $user->id);
                })
                ->pluck('id');

            $totalDeposit = $downlineIds->isEmpty()
                ? 0.0
                : (float) Recharge::query()
                    ->whereIn('uid', $downlineIds)
                    ->where('status', 2)
                    ->sum('num');

            $members = User::query()
                ->where($levelField, $user->id)
                ->orderByDesc('id')
                ->limit(100)
                ->get(['id', 'username']);

            $memberIds = $members->pluck('id');

            $referralCounts = $memberIds->isEmpty()
                ? collect()
                : User::query()
                    ->whereIn('invit_1', $memberIds)
                    ->selectRaw('invit_1 as uid, COUNT(*) as cnt')
                    ->groupBy('invit_1')
                    ->pluck('cnt', 'uid');

            $memberDeposits = $memberIds->isEmpty()
                ? collect()
                : Recharge::query()
                    ->whereIn('uid', $memberIds)
                    ->where('status', 2)
                    ->selectRaw('uid, SUM(num) as total')
                    ->groupBy('uid')
                    ->pluck('total', 'uid');

            $memberRows = $members->map(function (User $member) use ($referralCounts, $memberDeposits) {
                return [
                    'username' => $member->username,
                    'referral_count' => (int) ($referralCounts[$member->id] ?? 0),
                    'total_deposit' => number_format((float) ($memberDeposits[$member->id] ?? 0), 2, '.', ''),
                ];
            })->values()->all();

            $count1Rz = User::query()->where('invit_1', $user->id)->where('rzstatus', 2)->count();
            $count1Nrz = max(0, $levelOneCount - $count1Rz);
            $count2Rz = User::query()->where('invit_2', $user->id)->where('rzstatus', 2)->count();
            $count2Nrz = max(0, $levelTwoCount - $count2Rz);
            $count3Rz = User::query()->where('invit_3', $user->id)->where('rzstatus', 2)->count();
            $count3Nrz = max(0, $levelThreeCount - $count3Rz);

            $invit = (string) $user->invit;
            $feUrl = rtrim((string) env('FE_URL', ''), '/');
            $referralUrl = $feUrl !== ''
                ? $feUrl . '/register?invite=' . $invit
                : '/register?invite=' . $invit;

            return response()->json([
                'status' => true,
                'message' => 'Referral info retrieved successfully',
                'data' => [
                    'total_bonus' => number_format($totalBonus, 2, '.', ''),
                    'total_deposit' => number_format($totalDeposit, 2, '.', ''),
                    'level_one_count' => $levelOneCount,
                    'level_two_count' => $levelTwoCount,
                    'level_three_count' => $levelThreeCount,
                    'level' => $level,
                    'members' => $memberRows,
                    'invit' => $invit,
                    'referral_url' => $referralUrl,
                    'carr' => [
                        'one' => $count1Rz,
                        'two' => $count2Rz,
                        'three' => $count3Rz,
                        'onen' => $count1Nrz,
                        'twon' => $count2Nrz,
                        'threen' => $count3Nrz,
                        'allrz' => $count1Rz + $count2Rz + $count3Rz,
                        'allnrz' => $count1Nrz + $count2Nrz + $count3Nrz,
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Referral info retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve referral info. Try again later.',
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'gender' => 'required|integer|in:0,1',
                'dob' => 'required|date_format:d/m/Y',
                'country' => 'required|string|max:100',
                'phonenumber' => 'required|string|max:30',
                'loan' => 'required|string|in:cccd,driver_lisense,passport',
                'img_loan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:16384',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();

            $data = [
                'firstname' => trim($request->firstname),
                'lastname' => trim($request->lastname),
                'gender' => (int) $request->gender,
                'dob' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'country' => trim($request->country),
                'loan' => $request->loan,
                'phonenumber' => trim($request->phonenumber),
            ];

            if ($request->hasFile('img_loan')) {
                $imgLoanPath = $request->file('img_loan')->store('loans', 'public');
                $data['img_loan'] = asset('storage/' . $imgLoanPath);
            }

            $updated = User::where('id', $user->id)->update($data);

            if ($updated) {
                return response()->json([
                    'status' => true,
                    'message' => 'Updated profile successfully',
                    'data' => [
                        'firstname' => $data['firstname'],
                        'lastname' => $data['lastname'],
                        'gender' => $data['gender'],
                        'dob' => $data['dob'],
                        'country' => $data['country'],
                        'phonenumber' => $data['phonenumber'],
                        'loan' => $data['loan'],
                        'img_loan' => $data['img_loan'] ?? null,
                    ],
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No profile changes were saved',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('User profile update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Update profile failed. Please try again later.',
            ], 500);
        }
    }

    public function updateUserBankInfo(Request $request)
    {
        try {
            // Define basic validation rules
            $rules = [
                'wallet' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'bank_acc_no' => 'nullable|string|max:50',
                'bank_acc_name' => 'nullable|string|max:255',
            ];

            // Create validator instance
            $validator = Validator::make($request->all(), $rules);

            // Add conditional validation for bank fields
            $validator->sometimes(['bank_name', 'bank_acc_no', 'bank_acc_name'], 'required|string', function ($input) {
                // If wallet is filled, bank fields are not required
                if ($input->filled('wallet')) {
                    return false;
                }
                // If any bank field is filled, all bank fields are required
                return $input->filled('bank_name') || $input->filled('bank_acc_no') || $input->filled('bank_acc_name');
            });

            // Add max length rules for bank fields when required
            $validator->sometimes('bank_name', 'max:255', function ($input) {
                return $input->filled('bank_name');
            });
            $validator->sometimes('bank_acc_no', 'max:50', function ($input) {
                return $input->filled('bank_acc_no');
            });
            $validator->sometimes('bank_acc_name', 'max:255', function ($input) {
                return $input->filled('bank_acc_name');
            });

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();

            // Prepare data for update
            $data = [
                'bank_name' => $request->filled('bank_name') ? strtoupper($request->bank_name) : null,
                'bank_acc_no' => $request->bank_acc_no,
                'bank_acc_name' => $request->filled('bank_acc_name') ? strtoupper($request->bank_acc_name) : null,
                'wallet' => $request->wallet,
            ];

            // Update user
            $updated = $user->update(array_filter($data, function ($value) {
                return !is_null($value);
            }));

            if ($updated) {
                return response()->json([
                    'status' => true,
                    'message' => 'Updated user bank info successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Update user bank info failed. Please try again later.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('User bank info update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Update user bank info failed. Please try again later.',
            ], 500);
        }
    }
}
