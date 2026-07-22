<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tw_perp_position')) {
            Schema::table('tw_perp_position', function (Blueprint $table) {
                if (!Schema::hasColumn('tw_perp_position', 'kongyk')) {
                    $table->unsignedTinyInteger('kongyk')->default(0)->after('status');
                }
                if (!Schema::hasColumn('tw_perp_position', 'admin_notified')) {
                    $table->unsignedTinyInteger('admin_notified')->default(0)->after('kongyk');
                }
            });
        }

        if (Schema::hasTable('tw_hysetting')) {
            Schema::table('tw_hysetting', function (Blueprint $table) {
                if (!Schema::hasColumn('tw_hysetting', 'perp_win_rate')) {
                    $table->decimal('perp_win_rate', 8, 2)->default(80)->after('hy_sxf');
                }
            });
        }

        if (Schema::hasTable('tw_menu') && !DB::table('tw_menu')->where('url', 'Trade/perp')->exists()) {
            $sibling = DB::table('tw_menu')->where('url', 'Trade/index')->first();
            if ($sibling) {
                DB::table('tw_menu')->insert([
                    'title' => 'Perp leverage',
                    'pid' => $sibling->pid,
                    'sort' => ((int) $sibling->sort) + 1,
                    'url' => 'Trade/perp',
                    'hide' => 0,
                    'tip' => '',
                    'group' => $sibling->group,
                    'is_dev' => 0,
                    'ico_name' => $sibling->ico_name ?? 'list-alt',
                    'is_manager' => $sibling->is_manager ?? 0,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tw_menu')) {
            DB::table('tw_menu')->where('url', 'Trade/perp')->delete();
        }

        if (Schema::hasTable('tw_perp_position')) {
            Schema::table('tw_perp_position', function (Blueprint $table) {
                if (Schema::hasColumn('tw_perp_position', 'admin_notified')) {
                    $table->dropColumn('admin_notified');
                }
                if (Schema::hasColumn('tw_perp_position', 'kongyk')) {
                    $table->dropColumn('kongyk');
                }
            });
        }

        if (Schema::hasTable('tw_hysetting')) {
            Schema::table('tw_hysetting', function (Blueprint $table) {
                if (Schema::hasColumn('tw_hysetting', 'perp_win_rate')) {
                    $table->dropColumn('perp_win_rate');
                }
            });
        }
    }
};
