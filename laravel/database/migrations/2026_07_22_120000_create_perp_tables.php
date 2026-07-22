<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tw_perp_position')) {
            Schema::create('tw_perp_position', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('uid')->index();
                $table->string('username', 60);
                $table->string('symbol', 30)->index();
                $table->string('side', 10);
                $table->decimal('qty', 20, 10)->default(0);
                $table->decimal('entry_price', 20, 8)->default(0);
                $table->unsignedSmallInteger('leverage')->default(1);
                $table->decimal('margin', 20, 10)->default(0);
                $table->decimal('liq_price', 20, 8)->default(0);
                $table->decimal('unrealized_pnl', 20, 10)->default(0);
                $table->unsignedTinyInteger('status')->default(1)->index();
                $table->dateTime('opened_at')->nullable();
                $table->dateTime('closed_at')->nullable();
                $table->decimal('close_price', 20, 8)->nullable();
                $table->decimal('realized_pnl', 20, 10)->nullable();
                $table->index(['uid', 'symbol']);
            });
        }

        if (!Schema::hasTable('tw_perp_fill')) {
            Schema::create('tw_perp_fill', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('uid')->index();
                $table->unsignedInteger('position_id')->index();
                $table->string('symbol', 30);
                $table->string('side', 10);
                $table->string('action', 20);
                $table->decimal('qty', 20, 10);
                $table->decimal('price', 20, 8);
                $table->unsignedSmallInteger('leverage')->default(1);
                $table->decimal('margin_delta', 20, 10)->default(0);
                $table->decimal('fee', 20, 10)->default(0);
                $table->decimal('pnl', 20, 10)->default(0);
                $table->dateTime('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tw_perp_fill');
        Schema::dropIfExists('tw_perp_position');
    }
};
