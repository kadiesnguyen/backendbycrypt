<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\UserCoin;
use Illuminate\Console\Command;

use App\Models\Kjorder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EarningDailyKuangji extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:earning-daily-kuangji';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and distribute daily USDT earnings for active Kjorders based on Kuangji configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $kjorders = Kjorder::where('status', 1)
                ->where('endtime', '>', now())
                ->whereHas('kuangji', function ($query) {
                    $query->where('status', 1)
                        ->where('dayoutnum', '>', 0);
                })
                ->with('kuangji')
                ->get();

            $processed = 0;

            foreach ($kjorders as $kjorder) {
                $addtime = Carbon::parse($kjorder->addtime);
                $now = now();

                // Check if 24 hours have passed since addtime or last earning
                $lastEarning = $kjorder->last_earning_at ? Carbon::parse($kjorder->last_earning_at) : $addtime;

                if ($lastEarning->diffInHours($now) < 24) {
                    continue;
                }

                // Get daily USDT from Kuangji
                $dailyPi = $kjorder->kuangji->dayoutnum ?? 0;

                if ($dailyPi <= 0) {
                    Log::warning('No valid daily USDT for Kuangji', [
                        'kjorder_id' => $kjorder->id,
                        'kid' => $kjorder->kid,
                    ]);
                    continue;
                }

                // Add balance and log to Bill
                if ($this->addUserBalance($kjorder->uid, $dailyPi, $kjorder->kuangji->title)) {
                    // Update last earning time in Kjorder
                    $kjorder->update(['last_earning_at' => $now]);

                    // Log earning
                    Log::info('Daily USDT earning processed', [
                        'kjorder_id' => $kjorder->id,
                        'uid' => $kjorder->uid,
                        'pi_amount' => $dailyPi,
                        'earned_at' => $now->toDateTimeString(),
                    ]);

                    $processed++;
                }
            }

            DB::commit();

            $this->info("Processed $processed Kjorders successfully.");
            if ($processed) {
                Log::info('EarningDailyKuangji completed', ['processed' => $processed]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EarningDailyKuangji failed', ['error' => $e->getMessage()]);
            $this->error('Failed to process daily earnings: ' . $e->getMessage());
        }
    }
    protected function addUserBalance($userId, $amount, $kuangjiTitle)
    {
        try {
            $userCoin = UserCoin::where('userid', $userId)->lockForUpdate()->first();
            if ($userCoin) {
                $userCoin->increment('usdt', $amount);
                $user = User::find($userId);
                Bill::create([
                    'uid' => $user->id,
                    'username' => $user->username,
                    'num' => $amount,
                    'coinname' => 'usdt',
                    'afternum' => $userCoin->usdt,
                    'type' => 6,
                    'addtime' => now()->format('Y-m-d H:i:s'),
                    'st' => 1,
                    'remark' => 'Daily earning from Kuangji: ' . $kuangjiTitle,
                ]);

                Log::info("Added {$amount} USDT to user (From KuangJi) {$userId}", ['after_balance' => $userCoin->usdt]);

                return true;
            } else {
                Log::error("UserCoin not found for user {$userId}");
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to add balance for user {$userId}", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
