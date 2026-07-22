<?php

namespace App\Console\Commands;

use App\Services\BinanceTickerService;
use Illuminate\Console\Command;

class ExchangeQuoteSelfCheck extends Command
{
    protected $signature = 'app:exchange-quote-self-check';

    protected $description = 'Assert USDT exchange quote math.';

    public function handle(): int
    {
        $usdtToSol = BinanceTickerService::receiveAmount(100, 1.0, 80.0);
        if (abs($usdtToSol - 1.25) > 1e-9) {
            $this->error('USDT→coin quote math failed');

            return 1;
        }

        $solToUsdt = BinanceTickerService::receiveAmount(2, 80.0, 1.0);
        if (abs($solToUsdt - 160.0) > 1e-9) {
            $this->error('coin→USDT quote math failed');

            return 1;
        }

        $zero = BinanceTickerService::receiveAmount(10, 1.0, 0);
        if ($zero !== 0.0) {
            $this->error('zero toRate guard failed');

            return 1;
        }

        $ticker = new BinanceTickerService();
        if ($ticker->rateToUsdt('usdt') !== 1.0) {
            $this->error('usdt rate must be 1');

            return 1;
        }

        $this->info('Exchange quote self-check passed.');

        return 0;
    }
}
