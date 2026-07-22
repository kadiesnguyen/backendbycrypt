<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BinanceTickerService
{
    public function rateToUsdt(string $coinName): ?float
    {
        $coinName = strtolower(trim($coinName));

        if ($coinName === 'usdt') {
            return 1.0;
        }

        $tickerUrlTemplate = config('services.binance.ticker_url');

        if (!$tickerUrlTemplate) {
            return null;
        }

        $symbol = strtoupper($coinName);
        $url = str_replace('{symbol}', $symbol, $tickerUrlTemplate);

        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->ok()) {
                return null;
            }

            $price = (float) $response->json('price');

            return $price > 0 ? $price : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function receiveAmount(float $amount, float $fromRateUsdt, float $toRateUsdt): float
    {
        if ($amount <= 0 || $fromRateUsdt <= 0 || $toRateUsdt <= 0) {
            return 0.0;
        }

        return ($amount * $fromRateUsdt) / $toRateUsdt;
    }

    public function isTickerConfigured(): bool
    {
        return (bool) config('services.binance.ticker_url');
    }
}
