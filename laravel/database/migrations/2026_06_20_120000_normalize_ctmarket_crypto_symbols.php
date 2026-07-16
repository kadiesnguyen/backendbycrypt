<?php

use App\Support\TradingSymbol;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $markets = DB::table('tw_ctmarket')->get(['id', 'symbol']);

        foreach ($markets as $market) {
            $normalized = TradingSymbol::normalize((string) $market->symbol);

            if ($normalized === (string) $market->symbol) {
                continue;
            }

            DB::table('tw_ctmarket')->where('id', $market->id)->update([
                'symbol' => $normalized,
                'name' => strtolower($normalized),
            ]);
        }

        $orders = DB::table('tw_hyorder')
            ->where('coinname', 'like', '%-%')
            ->get(['id', 'coinname']);

        foreach ($orders as $order) {
            DB::table('tw_hyorder')->where('id', $order->id)->update([
                'coinname' => TradingSymbol::normalize((string) $order->coinname),
            ]);
        }
    }

    public function down(): void
    {
        // Data normalization is not safely reversible.
    }
};
