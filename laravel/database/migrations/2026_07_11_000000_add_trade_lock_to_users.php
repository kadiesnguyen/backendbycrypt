<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            if (!Schema::hasColumn('tw_user', 'trade_locked')) {
                $table->unsignedTinyInteger('trade_locked')->default(0)->after('txstate');
            }
            if (!Schema::hasColumn('tw_user', 'trade_lock_msg')) {
                $table->string('trade_lock_msg', 500)->nullable()->after('trade_locked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            if (Schema::hasColumn('tw_user', 'trade_lock_msg')) {
                $table->dropColumn('trade_lock_msg');
            }
            if (Schema::hasColumn('tw_user', 'trade_locked')) {
                $table->dropColumn('trade_locked');
            }
        });
    }
};
