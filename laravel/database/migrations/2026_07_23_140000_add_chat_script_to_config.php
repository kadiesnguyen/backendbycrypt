<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Default SaleSmartly embed used by /chat before this column existed. */
    private const DEFAULT_CHAT_SCRIPT = <<<'HTML'
<script>window.__ssc=window.__ssc||{};window.__ssc.license=window.__ssc.license||'g1vfb1b';</script>
<script src="https://plugin-code.salesmartly.com/js/project_783639_810552_1784725341.js"></script>
HTML;

    public function up(): void
    {
        if (! Schema::hasTable('tw_config')) {
            return;
        }

        if (! Schema::hasColumn('tw_config', 'chat_script')) {
            Schema::table('tw_config', function (Blueprint $table) {
                $table->mediumText('chat_script')->nullable()->after('telegram');
            });
        }

        DB::table('tw_config')
            ->where('id', 1)
            ->where(function ($query) {
                $query->whereNull('chat_script')->orWhere('chat_script', '');
            })
            ->update(['chat_script' => self::DEFAULT_CHAT_SCRIPT]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('tw_config') || ! Schema::hasColumn('tw_config', 'chat_script')) {
            return;
        }

        Schema::table('tw_config', function (Blueprint $table) {
            $table->dropColumn('chat_script');
        });
    }
};
