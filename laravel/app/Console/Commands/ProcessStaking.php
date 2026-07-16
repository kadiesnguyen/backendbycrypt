<?php

namespace App\Console\Commands;

use App\Models\Bill;
use App\Models\IssueLog;
use App\Models\UserCoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessStaking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-staking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process stakinng operations, including calculating rewards and updating user balances.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Lấy các bản ghi staking đang hoạt động và đã hết hạn
            $expiredStakings = IssueLog::where('status', 1)
                ->where('endtime', '<=', now()->format('Y-m-d H:i:s'))
                ->get();

            if ($expiredStakings->isEmpty()) {
                $this->info('No expired staking records found.');
                return 0;
            }

            $buyCoin = 'usdt'; // Coin mặc định
            foreach ($expiredStakings as $staking) {
                DB::beginTransaction();
                try {
                    // Lấy thông tin số dư người dùng
                    $userCoin = UserCoin::where('userid', $staking->uid)
                        ->lockForUpdate()
                        ->first();

                    if (!$userCoin) {
                        Log::warning('User coin not found', ['user_id' => $staking->uid, 'issue_log_id' => $staking->id]);
                        DB::rollBack();
                        continue;
                    }

                    $frozenColumn = $buyCoin . '_d';

                    // Kiểm tra số dư đóng băng
                    if ($userCoin->$frozenColumn < $staking->num) {
                        Log::error('Insufficient frozen balance', [
                            'user_id' => $staking->uid,
                            'issue_log_id' => $staking->id,
                            'frozen_balance' => $userCoin->$frozenColumn,
                            'required' => $staking->num,
                        ]);
                        DB::rollBack();
                        continue;
                    }

                    // Bỏ đóng băng: Giảm <buycoin>_d và cộng lại vào <buycoin>
                    $userCoin->decrement($frozenColumn, $staking->num);
                    $userCoin->increment($buyCoin, $staking->num);

                    // Ghi bill cho việc bỏ đóng băng
                    Bill::create([
                        'uid' => $staking->uid,
                        'username' => $staking->account,
                        'num' => $staking->num,
                        'coinname' => $buyCoin,
                        'afternum' => $userCoin->$buyCoin,
                        'type' => 8,
                        'addtime' => now()->format('Y-m-d H:i:s'),
                        'st' => 1, // Cộng
                        'remark' => $staking->name . ' staking completed',
                    ]);

                    // Tính và cộng lợi nhuận
                    $profit = ($staking->num * $staking->percent) / 100;
                    if ($profit > 0) {
                        $userCoin->increment($buyCoin, $profit);

                        // Ghi bill cho lợi nhuận
                        Bill::create([
                            'uid' => $staking->uid,
                            'username' => $staking->account,
                            'num' => $profit,
                            'coinname' => $buyCoin,
                            'afternum' => $userCoin->$buyCoin,
                            'type' => 12, // Lợi nhuận staking
                            'addtime' => now()->format('Y-m-d H:i:s'),
                            'st' => 1, // Cộng
                            'remark' => $staking->name . ' staking profit',
                        ]);
                    }

                    // Cập nhật trạng thái staking
                    $staking->update(['status' => 2]);

                    DB::commit();
                    Log::info('Processed staking successfully', [
                        'issue_log_id' => $staking->id,
                        'user_id' => $staking->uid,
                        'num' => $staking->num,
                        'profit' => $profit,
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to process staking', [
                        'issue_log_id' => $staking->id,
                        'user_id' => $staking->uid,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->info('Processed ' . $expiredStakings->count() . ' expired staking records.');
            return 0;

        } catch (\Exception $e) {
            Log::error('Staking cronjob failed', ['error' => $e->getMessage()]);
            $this->error('Cronjob failed: ' . $e->getMessage());
            return 1;
        }
    }
}
