<?php

namespace App\Console\Commands;

use App\Models\CheckinLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetCheckinStreak extends Command
{
    protected $signature = 'app:reset-checkin-streak';
    protected $description = 'Check and log users who missed their daily check-in streak';

    public function handle()
    {
        try {
            $yesterday = now()->subDay()->format('Y-m-d');
            $today = now()->format('Y-m-d');

            // Lấy các user đã điểm danh hôm qua nhưng chưa điểm danh hôm nay
            $usersMissedCheckin = CheckinLog::where('checkin_date', $yesterday)
                ->whereNotExists(function ($query) use ($today) {
                    $query->select(DB::raw(1))
                        ->from('tw_checkin_log as cl2')
                        ->where('cl2.checkin_date', $today)
                        ->whereColumn('cl2.uid', 'tw_checkin_log.uid');
                })
                ->pluck('uid')
                ->unique();

            if ($usersMissedCheckin->isEmpty()) {
                $this->info('No users missed their check-in streak today.');
                return 0;
            }

            // Ghi log cho admin
            foreach ($usersMissedCheckin as $userId) {
                $user = User::find($userId);
                if ($user) {
                    Log::info('User missed check-in streak', [
                        'user_id' => $userId,
                        'username' => $user->username,
                        'last_streak' => CheckinLog::where('uid', $userId)->orderBy('checkin_date', 'desc')->value('streak'),
                    ]);
                }
            }

            $this->info('Logged ' . $usersMissedCheckin->count() . ' users who missed their check-in streak.');
            return 0;

        } catch (\Exception $e) {
            Log::error('Reset check-in streak cronjob failed', ['error' => $e->getMessage()]);
            $this->error('Cronjob failed: ' . $e->getMessage());
            return 1;
        }
    }
}