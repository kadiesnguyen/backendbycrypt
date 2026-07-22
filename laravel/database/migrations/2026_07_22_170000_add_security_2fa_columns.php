<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            if (!Schema::hasColumn('tw_user', 'google2fa_secret')) {
                $table->string('google2fa_secret', 64)->nullable()->after('ui_locale');
            }
            if (!Schema::hasColumn('tw_user', 'google2fa_enabled')) {
                $table->unsignedTinyInteger('google2fa_enabled')->default(0)->after('google2fa_secret');
            }
            if (!Schema::hasColumn('tw_user', 'security_email')) {
                $table->string('security_email', 255)->nullable()->after('google2fa_enabled');
            }
            if (!Schema::hasColumn('tw_user', 'security_email_verified')) {
                $table->unsignedTinyInteger('security_email_verified')->default(0)->after('security_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tw_user', function (Blueprint $table) {
            foreach (['security_email_verified', 'security_email', 'google2fa_enabled', 'google2fa_secret'] as $col) {
                if (Schema::hasColumn('tw_user', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
