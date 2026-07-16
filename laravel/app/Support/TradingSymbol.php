<?php

namespace App\Support;

class TradingSymbol
{
    /**
     * Normalize trading symbols for APIs (Binance, TradingView, etc.).
     * Examples: btc-usdt → BTCUSDT, BTC/USDT → BTCUSDT, XAUUSD → XAUUSD
     */
    public static function normalize(string $symbol): string
    {
        return strtoupper(str_replace(['-', '/', '_'], '', trim($symbol)));
    }
}
