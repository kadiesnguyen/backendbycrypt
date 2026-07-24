<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tw_coin')) {
            return;
        }

        // float(10,2) rounded 0.015 → 0.02, so admin "couldn't save" fee rates.
        DB::statement('ALTER TABLE `tw_coin` MODIFY `czline` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `czaddress` VARCHAR(512) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `txsxf` DECIMAL(16, 8) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `txsxf_n` DECIMAL(16, 8) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `bbsxf` DECIMAL(16, 8) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `hysxf` DECIMAL(16, 8) NULL');

        // Withdrawal fee rate: 0.015 = 1.5% (same convention as bbsxf / client).
        DB::table('tw_coin')->update(['txsxf' => 0.015]);

        // Restore USDT spot fee after accidental float round-trip during debugging.
        DB::table('tw_coin')->where('name', 'usdt')->update(['bbsxf' => 0.03]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('tw_coin')) {
            return;
        }

        DB::statement('ALTER TABLE `tw_coin` MODIFY `czline` VARCHAR(50) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `czaddress` VARCHAR(225) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `txsxf` FLOAT(10, 2) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `txsxf_n` FLOAT(10, 2) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `bbsxf` FLOAT(10, 2) NULL');
        DB::statement('ALTER TABLE `tw_coin` MODIFY `hysxf` FLOAT(10, 2) NULL');
    }
};
