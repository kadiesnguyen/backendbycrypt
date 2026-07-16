<?php

namespace App\Console\Commands;

use App\Support\NotificationTtl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeExpiredNotices extends Command
{
    protected $signature = 'app:purge-expired-notices';

    protected $description = 'Delete user notices older than 24 hours';

    public function handle(): int
    {
        try {
            $deleted = NotificationTtl::purgeExpiredNotices();
            $this->info("Deleted {$deleted} expired notice(s).");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Purge expired notices failed', ['error' => $e->getMessage()]);
            $this->error('Purge failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
