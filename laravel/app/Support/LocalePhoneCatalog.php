<?php

namespace App\Support;

use InvalidArgumentException;

class LocalePhoneCatalog
{
    /**
     * App UI locales / phone countries (matches client login country picker).
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
            ['id' => 6, 'code' => 'po', 'name' => 'Português', 'phone_code' => '351', 'flag_url' => 'https://flagcdn.com/pt.svg'],
            ['id' => 7, 'code' => 'fr', 'name' => 'Français', 'phone_code' => '33', 'flag_url' => 'https://flagcdn.com/fr.svg'],
            ['id' => 8, 'code' => 'it', 'name' => 'Italiano', 'phone_code' => '39', 'flag_url' => 'https://flagcdn.com/it.svg'],
            ['id' => 9, 'code' => 'ko', 'name' => '한국어', 'phone_code' => '82', 'flag_url' => 'https://flagcdn.com/kr.svg'],
            ['id' => 10, 'code' => 'th', 'name' => 'ไทย', 'phone_code' => '66', 'flag_url' => 'https://flagcdn.com/th.svg'],
            ['id' => 11, 'code' => 'gr', 'name' => 'Ελληνικά', 'phone_code' => '30', 'flag_url' => 'https://flagcdn.com/gr.svg'],
            ['id' => 12, 'code' => 'zh-TW', 'name' => '繁體中文', 'phone_code' => '886', 'flag_url' => 'https://flagcdn.com/tw.svg'],
            ['id' => 13, 'code' => 'en-AU', 'name' => 'English (Australia)', 'phone_code' => '61', 'flag_url' => 'https://flagcdn.com/au.svg'],
            ['id' => 14, 'code' => 'pl', 'name' => 'Polski', 'phone_code' => '48', 'flag_url' => 'https://flagcdn.com/pl.svg'],
            ['id' => 15, 'code' => 'nl', 'name' => 'Nederlands', 'phone_code' => '31', 'flag_url' => 'https://flagcdn.com/nl.svg'],
            ['id' => 16, 'code' => 'en-SG', 'name' => 'English (Singapore)', 'phone_code' => '65', 'flag_url' => 'https://flagcdn.com/sg.svg'],
            ['id' => 17, 'code' => 'ms', 'name' => 'Bahasa Melayu', 'phone_code' => '60', 'flag_url' => 'https://flagcdn.com/my.svg'],
        ];
    }

    /**
     * Vietnamese display names for country picker when ?locale=vi.
     *
     * @return array<string, string>
     */
    public static function viNames(): array
    {
        return [
            'en' => 'Anh / Mỹ',
            'vi' => 'Việt Nam',
            'ja' => 'Nhật Bản',
            'id' => 'Indonesia',
            'de' => 'Đức',
            'es' => 'Tây Ban Nha',
            'po' => 'Bồ Đào Nha',
            'fr' => 'Pháp',
            'it' => 'Ý',
            'ko' => 'Hàn Quốc',
            'th' => 'Thái Lan',
            'gr' => 'Hy Lạp',
            'zh-TW' => 'Đài Loan',
            'en-AU' => 'Úc',
            'pl' => 'Ba Lan',
            'nl' => 'Hà Lan',
            'en-SG' => 'Singapore',
            'ms' => 'Malaysia',
        ];
    }

    /**
     * @return list<array{id: int, code: string, name: string, phone_code: string, flag_url: string}>
     */
    public static function entriesForLocale(?string $locale): array
    {
        $locale = strtolower(trim((string) $locale));
        $entries = self::entries();

        if ($locale !== 'vi') {
            return $entries;
        }

        $vi = self::viNames();
        foreach ($entries as &$entry) {
            if (isset($vi[$entry['code']])) {
                $entry['name'] = $vi[$entry['code']];
            }
        }
        unset($entry);

        return $entries;
    }

    public static function findByLocale(string $locale): ?array
    {
        $locale = trim($locale);
        foreach (self::entries() as $entry) {
            if (strcasecmp($entry['code'], $locale) === 0) {
                return $entry;
            }
        }

        return null;
    }

    public static function findByPhoneCode(string $phoneCode): ?array
    {
        $phoneCode = preg_replace('/\D/', '', $phoneCode);
        foreach (self::entries() as $entry) {
            if ($entry['phone_code'] === $phoneCode) {
                return $entry;
            }
        }

        return null;
    }

    public static function normalizeUiLocale(?string $locale): string
    {
        $locale = trim((string) $locale);
        if ($locale !== '' && self::findByLocale($locale) !== null) {
            $entry = self::findByLocale($locale);

            return $entry['code'] ?? 'vi';
        }

        return 'vi';
    }

    public static function resolveUiLocale(?string $locale, ?string $phoneCode = null): string
    {
        $locale = trim((string) $locale);
        if ($locale !== '' && self::findByLocale($locale) !== null) {
            return self::normalizeUiLocale($locale);
        }

        if ($phoneCode !== null && $phoneCode !== '') {
            $entry = self::findByPhoneCode($phoneCode);

            return $entry['code'] ?? 'vi';
        }

        return 'vi';
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
