<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tw_loan_setting')) {
            Schema::create('tw_loan_setting', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('enabled')->default(true);
                $table->decimal('min_amount', 20, 8)->default(1000);
                $table->decimal('max_amount', 20, 8)->default(200000);
                $table->unsignedInteger('duration_days')->default(7);
                $table->decimal('daily_interest_rate', 20, 10)->default(0.0004);
                $table->string('lender_name', 120)->default('ICICI BANK');
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (DB::table('tw_loan_setting')->count() === 0) {
            DB::table('tw_loan_setting')->insert([
                'id' => 1,
                'enabled' => 1,
                'min_amount' => 1000,
                'max_amount' => 200000,
                'duration_days' => 7,
                'daily_interest_rate' => 0.0004,
                'lender_name' => 'ICICI BANK',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if (!Schema::hasTable('tw_loan')) {
            Schema::create('tw_loan', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('username', 60)->index();
                $table->decimal('amount', 20, 8);
                $table->unsignedInteger('duration_days');
                $table->decimal('daily_interest_rate', 20, 10);
                $table->string('lender_name', 120);
                $table->decimal('interest_amount', 20, 8);
                $table->decimal('repay_amount', 20, 8);
                $table->string('status', 20)->index();
                $table->text('note')->nullable();
                $table->string('img_front', 500)->nullable();
                $table->string('img_back', 500)->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->dateTime('due_at')->nullable()->index();
                $table->dateTime('repaid_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'status']);
                $table->index(['status', 'due_at']);
            });
        }

        if (Schema::hasTable('tw_menu') && !DB::table('tw_menu')->where('url', 'Finance/loan')->exists()) {
            $sibling = DB::table('tw_menu')->where('url', 'Finance/myzc')->first();
            if ($sibling) {
                DB::table('tw_menu')->insert([
                    'title' => 'Vay hỗ trợ',
                    'pid' => $sibling->pid,
                    'sort' => ((int) $sibling->sort) + 1,
                    'url' => 'Finance/loan',
                    'hide' => 0,
                    'tip' => '',
                    'group' => $sibling->group,
                    'is_dev' => 0,
                    'ico_name' => $sibling->ico_name ?? 'icon-credit-card',
                    'is_manager' => $sibling->is_manager ?? 0,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tw_menu')) {
            DB::table('tw_menu')->where('url', 'Finance/loan')->delete();
        }

        Schema::dropIfExists('tw_loan');
        Schema::dropIfExists('tw_loan_setting');
    }
};
