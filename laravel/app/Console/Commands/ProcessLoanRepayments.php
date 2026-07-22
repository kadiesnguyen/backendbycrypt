<?php

namespace App\Console\Commands;

use App\Services\LoanService;
use Illuminate\Console\Command;

class ProcessLoanRepayments extends Command
{
    protected $signature = 'app:process-loan-repayments';

    protected $description = 'Auto-collect due/overdue loan repayments from USDT wallets.';

    public function handle(LoanService $loans): int
    {
        $count = $loans->processDueRepayments();
        $this->info("Settled {$count} loan(s).");

        return self::SUCCESS;
    }
}
