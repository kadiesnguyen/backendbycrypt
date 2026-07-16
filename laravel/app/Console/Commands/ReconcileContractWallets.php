<?php

namespace App\Console\Commands;

use App\Services\ContractWalletReconciliationService;
use Illuminate\Console\Command;

class ReconcileContractWallets extends Command
{
    protected $signature = 'app:reconcile-contract-wallets {--user= : Reconcile a single user id}';

    protected $description = 'Fix contract settlement bills and wallet credits (win: stake+profit, loss: stake-loss)';

    public function handle(ContractWalletReconciliationService $reconciliation): int
    {
        $userId = $this->option('user');
        $userFilter = $userId !== null && $userId !== '' ? (int) $userId : null;

        $result = $reconciliation->reconcile($userFilter);

        $this->info("Orders checked: {$result['orders_checked']}");
        $this->info("Bills fixed: {$result['bills_fixed']}");

        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        return $result['errors'] === [] ? 0 : 1;
    }
}
