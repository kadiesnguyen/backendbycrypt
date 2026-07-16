<?php

namespace App\Support;

class CoreConfig
{
    /**
     * @return array<int, string>
     */
    public static function indexCategories(): array
    {
        $path = base_path('../thinkphp-admin/Database/core.json');

        if (!is_file($path)) {
            return ['USDT', 'CNC', 'BTC', 'ETH'];
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return ['USDT', 'CNC', 'BTC', 'ETH'];
        }

        $raw = preg_replace('/\/\*[\s\S]+?\*\//', '', $raw);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded) || !isset($decoded['indexcat']) || !is_array($decoded['indexcat'])) {
            return ['USDT', 'CNC', 'BTC', 'ETH'];
        }

        return array_values($decoded['indexcat']);
    }
}
