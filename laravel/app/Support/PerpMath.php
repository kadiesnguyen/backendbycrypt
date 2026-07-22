<?php

namespace App\Support;

/**
 * Perpetual margin / liquidation math (v1).
 */
final class PerpMath
{
    public static function notional(float $qty, float $mark): float
    {
        return max($qty, 0) * max($mark, 0);
    }

    public static function marginRequired(float $notional, int $leverage): float
    {
        $lev = max($leverage, 1);

        return $notional / $lev;
    }

    public static function fee(float $notional, float $feeRatePercent): float
    {
        if ($feeRatePercent <= 0 || $notional <= 0) {
            return 0.0;
        }

        return $notional * ($feeRatePercent / 100);
    }

    public static function liquidationPrice(
        string $side,
        float $entry,
        int $leverage,
        float $maintMarginRate
    ): float {
        $lev = max($leverage, 1);
        $entry = max($entry, 0);
        $mm = max($maintMarginRate, 0);

        if ($entry <= 0) {
            return 0.0;
        }

        if ($side === 'long') {
            return $entry * (1 - (1 / $lev) + $mm);
        }

        return $entry * (1 + (1 / $lev) - $mm);
    }

    public static function unrealizedPnl(string $side, float $entry, float $mark, float $qty): float
    {
        if ($qty <= 0 || $entry <= 0) {
            return 0.0;
        }

        if ($side === 'long') {
            return ($mark - $entry) * $qty;
        }

        return ($entry - $mark) * $qty;
    }

    public static function shouldLiquidate(string $side, float $mark, float $liqPrice): bool
    {
        if ($liqPrice <= 0 || $mark <= 0) {
            return false;
        }

        if ($side === 'long') {
            return $mark <= $liqPrice;
        }

        return $mark >= $liqPrice;
    }

    public static function weightedEntry(float $oldQty, float $oldEntry, float $addQty, float $addPrice): float
    {
        $totalQty = $oldQty + $addQty;
        if ($totalQty <= 0) {
            return 0.0;
        }

        return (($oldQty * $oldEntry) + ($addQty * $addPrice)) / $totalQty;
    }
}
