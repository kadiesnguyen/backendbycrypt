<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Loan;
use App\Models\LoanSetting;
use App\Models\Notice;
use App\Models\User;
use App\Models\UserCoin;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LoanService
{
    public const BILL_TYPE_DISBURSEMENT = 18;
    public const BILL_TYPE_REPAYMENT = 19;

    public function settings(): LoanSetting
    {
        $settings = LoanSetting::query()->find(1);

        if (!$settings) {
            DB::table('tw_loan_setting')->insert([
                'id' => 1,
                'enabled' => 1,
                'min_amount' => 1000,
                'max_amount' => 200000,
                'duration_days' => 7,
                'daily_interest_rate' => 0.0004,
                'lender_name' => 'ICICI BANK',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
            $settings = LoanSetting::query()->find(1);
        }

        return $settings;
    }

    public function updateSettings(array $payload): LoanSetting
    {
        $settings = $this->settings();
        $settings->fill([
            'enabled' => (bool) ($payload['enabled'] ?? $settings->enabled),
            'min_amount' => $payload['min_amount'] ?? $settings->min_amount,
            'max_amount' => $payload['max_amount'] ?? $settings->max_amount,
            'duration_days' => (int) ($payload['duration_days'] ?? $settings->duration_days),
            'daily_interest_rate' => $payload['daily_interest_rate'] ?? $settings->daily_interest_rate,
            'lender_name' => trim((string) ($payload['lender_name'] ?? $settings->lender_name)),
            'updated_at' => now(),
        ]);
        $settings->save();

        return $settings->fresh();
    }

    /**
     * @return array{interest_amount: string, repay_amount: string}
     */
    public function calcAmounts(float $amount, float $dailyRate, int $days): array
    {
        $interest = round($amount * $dailyRate * $days, 8);
        $repay = round($amount + $interest, 8);

        return [
            'interest_amount' => number_format($interest, 8, '.', ''),
            'repay_amount' => number_format($repay, 8, '.', ''),
        ];
    }

    /**
     * @return array{can_apply: bool, reason: string|null}
     */
    public function canApply(User $user): array
    {
        $settings = $this->settings();

        if (!$settings->enabled) {
            return ['can_apply' => false, 'reason' => 'Loan feature is disabled.'];
        }

        if ((int) $user->rzstatus !== 2) {
            return ['can_apply' => false, 'reason' => 'KYC must be approved before applying.'];
        }

        $open = Loan::query()
            ->where('user_id', $user->id)
            ->whereIn('status', Loan::OPEN_STATUSES)
            ->exists();

        if ($open) {
            return ['can_apply' => false, 'reason' => 'You already have an open loan.'];
        }

        return ['can_apply' => true, 'reason' => null];
    }

    /**
     * @return array<string, mixed>
     */
    public function configForUser(User $user): array
    {
        $settings = $this->settings();
        $gate = $this->canApply($user);
        $sampleAmount = (float) $settings->min_amount;
        $calc = $this->calcAmounts(
            $sampleAmount,
            (float) $settings->daily_interest_rate,
            (int) $settings->duration_days,
        );

        return [
            'enabled' => (bool) $settings->enabled,
            'min_amount' => (string) $settings->min_amount,
            'max_amount' => (string) $settings->max_amount,
            'duration_days' => (int) $settings->duration_days,
            'daily_interest_rate' => (string) $settings->daily_interest_rate,
            'daily_interest_rate_percent' => rtrim(rtrim(number_format((float) $settings->daily_interest_rate * 100, 6, '.', ''), '0'), '.') . '%',
            'lender_name' => $settings->lender_name,
            'sample_interest_amount' => $calc['interest_amount'],
            'sample_repay_amount' => $calc['repay_amount'],
            'can_apply' => $gate['can_apply'],
            'cannot_apply_reason' => $gate['reason'],
            'currency' => 'USDT',
        ];
    }

    public function apply(User $user, float $amount, UploadedFile $front, UploadedFile $back): Loan
    {
        $gate = $this->canApply($user);
        if (!$gate['can_apply']) {
            throw new RuntimeException($gate['reason'] ?? 'Cannot apply.');
        }

        $settings = $this->settings();
        $min = (float) $settings->min_amount;
        $max = (float) $settings->max_amount;

        if ($amount < $min || $amount > $max) {
            throw new RuntimeException('Amount must be between min and max.');
        }

        $calc = $this->calcAmounts(
            $amount,
            (float) $settings->daily_interest_rate,
            (int) $settings->duration_days,
        );

        $frontPath = $front->store('loans', 'public');
        $backPath = $back->store('loans', 'public');

        return Loan::query()->create([
            'user_id' => $user->id,
            'username' => $user->username,
            'amount' => number_format($amount, 8, '.', ''),
            'duration_days' => (int) $settings->duration_days,
            'daily_interest_rate' => $settings->daily_interest_rate,
            'lender_name' => $settings->lender_name,
            'interest_amount' => $calc['interest_amount'],
            'repay_amount' => $calc['repay_amount'],
            'status' => Loan::STATUS_PENDING,
            'img_front' => asset('storage/' . $frontPath),
            'img_back' => asset('storage/' . $backPath),
        ]);
    }

    public function approve(int $loanId, ?string $note = null): Loan
    {
        return DB::transaction(function () use ($loanId, $note): Loan {
            /** @var Loan|null $loan */
            $loan = Loan::query()->lockForUpdate()->find($loanId);

            if (!$loan) {
                throw new RuntimeException('not_found');
            }

            if ($loan->status !== Loan::STATUS_PENDING) {
                throw new RuntimeException('processed');
            }

            $userCoin = UserCoin::query()
                ->where('userid', $loan->user_id)
                ->lockForUpdate()
                ->first();

            if (!$userCoin) {
                throw new RuntimeException('no_wallet');
            }

            $now = now();
            $before = (float) ($userCoin->usdt ?? 0);
            $amount = (float) $loan->amount;

            $loan->update([
                'status' => Loan::STATUS_ACTIVE,
                'note' => $note,
                'approved_at' => $now,
                'due_at' => $now->copy()->addDays((int) $loan->duration_days),
            ]);

            UserCoin::query()->where('userid', $loan->user_id)->increment('usdt', $amount);

            Bill::query()->create([
                'uid' => $loan->user_id,
                'username' => $loan->username,
                'num' => $amount,
                'coinname' => 'usdt',
                'afternum' => $before + $amount,
                'type' => self::BILL_TYPE_DISBURSEMENT,
                'addtime' => $now->format('Y-m-d H:i:s'),
                'st' => 1,
                'remark' => 'Loan disbursement #' . $loan->id,
            ]);

            Notice::query()->create([
                'uid' => $loan->user_id,
                'account' => $loan->username,
                'title' => 'Vay hỗ trợ',
                'content' => 'Yêu cầu vay của bạn đã được phê duyệt. Số tiền đã được cộng vào ví USDT.',
                'addtime' => $now->format('Y-m-d H:i:s'),
                'status' => 1,
            ]);

            return $loan->fresh();
        });
    }

    public function reject(int $loanId, ?string $note = null): Loan
    {
        return DB::transaction(function () use ($loanId, $note): Loan {
            /** @var Loan|null $loan */
            $loan = Loan::query()->lockForUpdate()->find($loanId);

            if (!$loan) {
                throw new RuntimeException('not_found');
            }

            if ($loan->status !== Loan::STATUS_PENDING) {
                throw new RuntimeException('processed');
            }

            $now = now()->format('Y-m-d H:i:s');

            $loan->update([
                'status' => Loan::STATUS_REJECTED,
                'note' => $note,
            ]);

            Notice::query()->create([
                'uid' => $loan->user_id,
                'account' => $loan->username,
                'title' => 'Vay hỗ trợ',
                'content' => 'Yêu cầu vay của bạn đã bị từ chối.' . ($note ? (' Ghi chú: ' . $note) : ''),
                'addtime' => $now,
                'status' => 1,
            ]);

            return $loan->fresh();
        });
    }

    public function processDueRepayments(): int
    {
        $ids = Loan::query()
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->where('status', Loan::STATUS_ACTIVE)
                        ->where('due_at', '<=', now());
                })->orWhere('status', Loan::STATUS_OVERDUE);
            })
            ->orderBy('id')
            ->pluck('id');

        $settled = 0;

        foreach ($ids as $id) {
            if ($this->settleOne((int) $id)) {
                $settled++;
            }
        }

        return $settled;
    }

    /**
     * @return bool true when loan became repaid
     */
    public function settleOne(int $loanId): bool
    {
        return DB::transaction(function () use ($loanId): bool {
            /** @var Loan|null $loan */
            $loan = Loan::query()->lockForUpdate()->find($loanId);

            if (!$loan) {
                return false;
            }

            $isDueActive = $loan->status === Loan::STATUS_ACTIVE
                && $loan->due_at
                && $loan->due_at->lte(now());
            $isOverdue = $loan->status === Loan::STATUS_OVERDUE;

            if (!$isDueActive && !$isOverdue) {
                return false;
            }

            $userCoin = UserCoin::query()
                ->where('userid', $loan->user_id)
                ->lockForUpdate()
                ->first();

            if (!$userCoin) {
                if ($loan->status === Loan::STATUS_ACTIVE) {
                    $this->markOverdue($loan);
                }

                return false;
            }

            $balance = (float) ($userCoin->usdt ?? 0);
            $repay = (float) $loan->repay_amount;
            $now = now();

            if ($balance < $repay) {
                if ($loan->status === Loan::STATUS_ACTIVE) {
                    $this->markOverdue($loan);
                }

                return false;
            }

            UserCoin::query()->where('userid', $loan->user_id)->decrement('usdt', $repay);

            $loan->update([
                'status' => Loan::STATUS_REPAID,
                'repaid_at' => $now,
            ]);

            Bill::query()->create([
                'uid' => $loan->user_id,
                'username' => $loan->username,
                'num' => $repay,
                'coinname' => 'usdt',
                'afternum' => $balance - $repay,
                'type' => self::BILL_TYPE_REPAYMENT,
                'addtime' => $now->format('Y-m-d H:i:s'),
                'st' => 1,
                'remark' => 'Loan repayment #' . $loan->id,
            ]);

            Notice::query()->create([
                'uid' => $loan->user_id,
                'account' => $loan->username,
                'title' => 'Vay hỗ trợ',
                'content' => 'Khoản vay #' . $loan->id . ' đã được tất toán. Đã trừ ' . $repay . ' USDT.',
                'addtime' => $now->format('Y-m-d H:i:s'),
                'status' => 1,
            ]);

            return true;
        });
    }

    private function markOverdue(Loan $loan): void
    {
        $loan->update(['status' => Loan::STATUS_OVERDUE]);

        Notice::query()->create([
            'uid' => $loan->user_id,
            'account' => $loan->username,
            'title' => 'Vay hỗ trợ',
            'content' => 'Khoản vay #' . $loan->id . ' đã quá hạn. Vui lòng đảm bảo đủ số dư USDT để hệ thống thu nợ.',
            'addtime' => now()->format('Y-m-d H:i:s'),
            'status' => 1,
        ]);
    }
}
