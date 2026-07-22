<?php

namespace App\Support;

use InvalidArgumentException;

class LocalePhoneCatalog
{
    /**
     * App UI locales mapped to default country dial codes (matches client language list).
     *
     * @return list<array{id: int, code: string, name: string, phone_code: string, flag_url: string}>
     */
    public static function entries(): array
    {
        return [
            ['id' => 0, 'code' => 'en', 'name' => 'English', 'phone_code' => '1', 'flag_url' => 'https://flagcdn.com/us.svg'],
            ['id' => 1, 'code' => 'vi', 'name' => 'Tiếng Việt', 'phone_code' => '84', 'flag_url' => 'https://flagcdn.com/vn.svg'],
            ['id' => 2, 'code' => 'ja', 'name' => '日本語', 'phone_code' => '81', 'flag_url' => 'https://flagcdn.com/jp.svg'],
            ['id' => 3, 'code' => 'id', 'name' => 'Bahasa Indonesia', 'phone_code' => '62', 'flag_url' => 'https://flagcdn.com/id.svg'],
            ['id' => 4, 'code' => 'de', 'name' => 'Deutsch', 'phone_code' => '49', 'flag_url' => 'https://flagcdn.com/de.svg'],
            ['id' => 5, 'code' => 'es', 'name' => 'Español', 'phone_code' => '34', 'flag_url' => 'https://flagcdn.com/es.svg'],
            ['id' => 6, 'code' => 'po', 'name' => 'Portugal', 'phone_code' => '351', 'flag_url' => 'https://flagcdn.com/pt.svg'],
            ['id' => 7, 'code' => 'fr', 'name' => 'Français', 'phone_code' => '33', 'flag_url' => 'https://flagcdn.com/fr.svg'],
            ['id' => 8, 'code' => 'it', 'name' => 'Italiano', 'phone_code' => '39', 'flag_url' => 'https://flagcdn.com/it.svg'],
            ['id' => 9, 'code' => 'ko', 'name' => '한국인', 'phone_code' => '82', 'flag_url' => 'https://flagcdn.com/kr.svg'],
            ['id' => 10, 'code' => 'th', 'name' => 'ไทย', 'phone_code' => '66', 'flag_url' => 'https://flagcdn.com/th.svg'],
            ['id' => 11, 'code' => 'gr', 'name' => 'Ελληνικά', 'phone_code' => '30', 'flag_url' => 'https://flagcdn.com/gr.svg'],
        ];
    }

    public static function findByLocale(string $locale): ?array
    {
        foreach (self::entries() as $entry) {
            if ($entry['code'] === $locale) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Normalize login username: email (lowercase) or phone storage format.
     */
    public static function normalizeUsername(string $raw, ?string $phoneCode = null): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            throw new InvalidArgumentException('empty');
        }

        if (filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            return strtolower($raw);
        }

        $digits = preg_replace('/\D/', '', $raw);
        $phoneCode = preg_replace('/\D/', '', (string) $phoneCode);

        if ($phoneCode === '' && preg_match('/^0\d{9}$/', $digits)) {
            return $digits;
        }

        if ($phoneCode === '84') {
            if (preg_match('/^0\d{9}$/', $digits)) {
                return $digits;
            }
            if (preg_match('/^\d{9}$/', $digits)) {
                return '0'.$digits;
            }

            throw new InvalidArgumentException('invalid_vn_phone');
        }

        if ($phoneCode === '' || strlen($digits) < 6 || strlen($digits) > 15) {
            throw new InvalidArgumentException('invalid_intl_phone');
        }

        return '+'.$phoneCode.$digits;
    }

    public static function isValidLoginIdentifier(string $raw, ?string $phoneCode = null): bool
    {
        try {
            self::normalizeUsername($raw, $phoneCode);

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }
}
