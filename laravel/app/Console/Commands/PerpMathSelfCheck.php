<?php

namespace App\Console\Commands;

use App\Support\PerpMath;
use Illuminate\Console\Command;

class PerpMathSelfCheck extends Command
{
    protected $signature = 'app:perp-math-self-check';

    protected $description = 'Assert perpetual margin and liquidation math.';

    public function handle(): int
    {
        $margin = PerpMath::marginRequired(1000, 10);
        if (abs($margin - 100) > 1e-6) {
            $this->error('marginRequired failed');

            return 1;
        }

        $liqLong = PerpMath::liquidationPrice('long', 100, 10, 0.005);
        $expectedLong = 100 * (1 - 0.1 + 0.005);
        if (abs($liqLong - $expectedLong) > 1e-6) {
            $this->error('liquidationPrice long failed');

            return 1;
        }

        $liqShort = PerpMath::liquidationPrice('short', 100, 10, 0.005);
        $expectedShort = 100 * (1 + 0.1 - 0.005);
        if (abs($liqShort - $expectedShort) > 1e-6) {
            $this->error('liquidationPrice short failed');

            return 1;
        }

        if (!PerpMath::shouldLiquidate('long', 90, 91)) {
            $this->error('shouldLiquidate long failed');

            return 1;
        }

        $this->info('Perp math self-check passed.');

        return 0;
    }
}
