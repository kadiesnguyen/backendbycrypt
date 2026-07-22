<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Support\LocalePhoneCatalog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function config(Request $request)
    {
        try {
            $config = Config::first();
            $configData = $config->toArray();
            $configData['checkin_notify'] = htmlspecialchars_decode($config['checkin_notify'], ENT_QUOTES);

            return response()->json([
                'status' => true,
                'message' => 'Configuration retrieved successfully.',
                'data' => $configData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Dial codes aligned with client UI locales (login/signup country picker).
     */
    public function localePhones(Request $request)
    {
        $locale = (string) $request->query('locale', 'vi');
        $default = LocalePhoneCatalog::findByLocale($locale)
            ?? LocalePhoneCatalog::findByLocale('vi')
            ?? LocalePhoneCatalog::entries()[0];

        return response()->json([
            'status' => true,
            'message' => 'Locale phone list retrieved successfully.',
            'data' => [
                'locales' => LocalePhoneCatalog::entries(),
                'default' => $default,
            ],
        ]);
    }
}
