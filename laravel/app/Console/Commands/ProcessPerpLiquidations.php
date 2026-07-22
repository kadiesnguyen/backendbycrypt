<?php

namespace App\Console\Commands;

use App\Services\PerpTradingService;
use Illuminate\Console\Command;

class ProcessPerpLiquidations extends Command
{
    protected $signature = 'app:process-perp-liquidations';

    protected $description = 'Liquidate open perpetual positions when mark price crosses liq price.';

    public function handle(PerpTradingService $perp): int
    {
        $count = $perp->processLiquidations();
        $this->info("Liquidated {$count} position(s).");

        return 0;
    }
}
