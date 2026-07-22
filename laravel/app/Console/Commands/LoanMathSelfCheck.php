<?php

namespace App\Console\Commands;

use App\Services\LoanService;
use Illuminate\Console\Command;

class LoanMathSelfCheck extends Command
{
    protected $signature = 'app:loan-math-self-check';

    protected $description = 'Assert loan interest math (amount * daily_rate * days).';

    public function handle(LoanService $loans): int
    {
        $calc = $loans->calcAmounts(200000, 0.0004, 7);
        $expectedInterest = '560.00000000';
        $expectedRepay = '200560.00000000';

        if ($calc['interest_amount'] !== $expectedInterest) {
            $this->error("interest mismatch: got {$calc['interest_amount']}, want {$expectedInterest}");

            return self::FAILURE;
        }

        if ($calc['repay_amount'] !== $expectedRepay) {
            $this->error("repay mismatch: got {$calc['repay_amount']}, want {$expectedRepay}");

            return self::FAILURE;
        }

        $this->info('Loan math self-check OK.');

        return self::SUCCESS;
    }
}
