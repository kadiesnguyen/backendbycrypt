<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            if (!Schema::hasColumn('tw_user', 'ui_locale')) {
                $table->string('ui_locale', 8)->nullable()->default('vi')->after('username');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            if (Schema::hasColumn('tw_user', 'ui_locale')) {
                $table->dropColumn('ui_locale');
            }
        });
    }
};
