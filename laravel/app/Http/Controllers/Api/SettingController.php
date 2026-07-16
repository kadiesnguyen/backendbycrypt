<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
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
}
