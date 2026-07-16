<?php

namespace App\Console\Commands;

use App\Services\HyorderSettlementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessHyOrders extends Command
{
    protected $signature = 'app:process-orders';

    protected $description = 'Process expired hyorders and update status, sellprice, and is_win for short orders';

    public function handle(HyorderSettlementService $settlement): int
    {
        try {
            $result = $settlement->settleDueOrders();

            if ($result['settled'] > 0) {
                $this->info("Settled {$result['settled']} order(s).");
            }

            if ($result['failed'] > 0) {
                $this->warn("Failed to settle {$result['failed']} order(s).");
            }

            Log::info('ProcessHyOrders finished', $result);

            return 0;
        } catch (\Exception $e) {
            Log::error('Failed to process hyorders', ['error' => $e->getMessage()]);
            $this->error('An error occurred while processing hyorders.');

            return 1;
        }
    }
}
