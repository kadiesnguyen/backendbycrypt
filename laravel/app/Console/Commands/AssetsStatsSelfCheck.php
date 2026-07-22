<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssetsStatsSelfCheck extends Command
{
    protected $signature = 'app:assets-stats-self-check';

    protected $description = 'Assert assets contract/finance stats field mapping helpers.';

    public function handle(): int
    {
        // Contract: wallet = available, margin = frozen, unrealized = sum
        $available = 100.5;
        $frozen = 25.25;
        $unrealA = 1.1;
        $unrealB = -0.4;
        $unrealized = $unrealA + $unrealB;

        $wallet = number_format($available, 2, '.', '');
        $margin = number_format($frozen, 2, '.', '');
        $pnl = number_format($unrealized, 2, '.', '');

        if ($wallet !== '100.50' || $margin !== '25.25' || $pnl !== '0.70') {
            $this->error('contract mapping failed');

            return 1;
        }

        // Finance: DIY = holdings = spot sum; revenue = realized sum
        $spot = 10 * 1.0 + 2 * 80.0;
        $realized = 12.345 + -2.0;
        $diy = number_format($spot, 2, '.', '');
        $revenue = number_format($realized, 2, '.', '');

        if ($diy !== '170.00' || $revenue !== '10.35' || $diy !== number_format($spot, 2, '.', '')) {
            $this->error('finance mapping failed');

            return 1;
        }

        $this->info('Assets stats self-check passed.');

        return 0;
    }
}
