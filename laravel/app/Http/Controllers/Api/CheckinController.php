<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\CheckinLog;
use App\Models\Config;
use App\Models\UserCoin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckinController extends Controller
{
    public function checkin(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Check if user has already checked in today
            $today = now()->format('Y-m-d');
            $existingCheckin = CheckinLog::where('uid', $user->id)
                ->where('checkin_date', $today)
                ->exists();

            if ($existingCheckin) {
                return response()->json([
                    'code' => 0,
                    'info' => 'You have already checked in today.',
                ], 422);
            }

            // Get check-in rewards configuration
            $setting = Config::select('checkin_rewards')->first();
            if (!$setting || empty($setting->checkin_rewards)) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Error in check-in configuration. Please contact support.',
                ], 500);
            }

            $rewards = explode(',', trim($setting->checkin_rewards));
            if (empty($rewards)) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Error in check-in rewards configuration. Please contact support.',
                ], 500);
            }

            // Get last check-in to calculate streak
            $lastCheckin = CheckinLog::where('uid', $user->id)
                ->orderBy('checkin_date', 'desc')
                ->first();

            $streak = 1;
            if ($lastCheckin) {
                $lastCheckinDate = $lastCheckin->checkin_date->format('Y-m-d');
                $yesterday = now()->subDay()->format('Y-m-d');

                if ($lastCheckinDate === $yesterday) {
                    $streak = $lastCheckin->streak + 1;
                }
            }

            // Determine reward based on streak
            $rewardIndex = min($streak - 1, count($rewards) - 1);
            $reward = (float) $rewards[$rewardIndex];

            // Start transaction
            DB::beginTransaction();

            // Create check-in log
            CheckinLog::create([
                'uid' => $user->id,
                'username' => $user->username,
                'streak' => $streak,
                'reward' => $reward,
                'checkin_date' => $today,
                'addtime' => now()->format('Y-m-d H:i:s'),
            ]);

            // Update user coin balance
            $userCoin = UserCoin::where('userid', $user->id)->lockForUpdate()->first();
            if (!$userCoin) {
                DB::rollBack();
                return response()->json([
                    'code' => 0,
                    'info' => 'Error updating user coin balance. Please contact support.',
                ], 500);
            }

            $buyCoin = 'usdt';
            $userCoin->increment($buyCoin, $reward);

            // Create bill for reward
            Bill::create([
                'uid' => $user->id,
                'username' => $user->username,
                'num' => $reward,
                'coinname' => $buyCoin,
                'afternum' => $userCoin->$buyCoin,
                'type' => 7, // Check-in reward
                'addtime' => now()->format('Y-m-d H:i:s'),
                'st' => 1,
                'remark' => 'Checkin day ' . $streak . ' in a row: ' . now()->format('H:i:s d/m/Y'),
            ]);

            DB::commit();

            return response()->json([
                'code' => 1,
                'info' => 'Check-in successful.',
                'data' => [
                    'streak' => $streak,
                    'reward' => number_format($reward, 8, '.', ''),
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-in failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'code' => 0,
                'info' => 'An error occurred during check-in. Please try again later.',
            ], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Validate month parameter
            $month = $request->query('month');
            $query = CheckinLog::where('uid', $user->id)
                ->select('checkin_date', 'streak', 'reward')
                ->orderBy('checkin_date');

            if ($month) {
                // Validate month format (YYYY-MM)
                if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                    return response()->json([
                        'code' => 0,
                        'info' => 'Format invalid. Please use YYYY-MM format.',
                    ], 422);
                }

                try {
                    $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                    $endOfMonth = $startOfMonth->copy()->endOfMonth();

                    $query->whereBetween('checkin_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
                } catch (\Exception $e) {
                    return response()->json([
                        'code' => 0,
                        'info' => 'Month format invalid. Please use YYYY-MM format.',
                    ], 422);
                }
            }

            // Get current streak and last check-in date
            $lastCheckin = CheckinLog::where('uid', $user->id)
                ->orderBy('checkin_date', 'desc')
                ->first();

            $currentStreak = 0;
            $lastCheckinDate = null;
            $firstCheckinDate = null;
            $hasCheckedInToday = CheckinLog::where('uid', $user->id)
                ->where('checkin_date', now()->format('Y-m-d'))
                ->exists();

            if ($lastCheckin) {
                $lastCheckinDate = $lastCheckin->checkin_date->format('Y-m-d');
                $yesterday = now()->subDay()->format('Y-m-d');

                if ($lastCheckinDate === now()->format('Y-m-d') || $lastCheckinDate === $yesterday) {
                    $currentStreak = $lastCheckin->streak;
                }
            }

            // Get check-in history based on streak
            $history = [];
            if ($currentStreak === 1) {
                // Only include the last check-in
                if ($lastCheckin) {
                    $history = [
                        [
                            'checkin_date' => $lastCheckin->checkin_date->format('Y-m-d'),
                            'streak' => $lastCheckin->streak,
                            'reward' => number_format($lastCheckin->reward ?? 0, 2, '.', ''),
                        ]
                    ];
                }
            } else {
                // Include all check-ins (filtered by month if specified)
                $history = $query->get()->map(function ($item) {
                    return [
                        'checkin_date' => $item->checkin_date->format('Y-m-d'),
                        'streak' => $item->streak,
                        'reward' => number_format($item->reward ?? 0, 2, '.', ''),
                    ];
                })->toArray();
                // Get first check-in date only if streak > 1
                $firstCheckin = CheckinLog::where('uid', $user->id)
                    ->orderBy('checkin_date', 'asc')
                    ->first();
                if ($firstCheckin) {
                    $firstCheckinDate = $firstCheckin->checkin_date->format('Y-m-d');
                }
            }

            return response()->json([
                'code' => 1,
                'info' => 'Get check-in history successfully.',
                'data' => [
                    'current_streak' => $currentStreak,
                    'last_checkin_date' => $lastCheckinDate,
                    'first_checkin_date' => $currentStreak === 1 ? null : $firstCheckinDate,
                    'has_checked_in_today' => $hasCheckedInToday,
                    'history' => $history,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Check-in history retrieval failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'code' => 0,
                'info' => 'Get check-in history failed. Please try again later.',
            ], 500);
        }
    }
}
