<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSpotSettingRequest;
use App\Http\Resources\Admin\SpotSettingResource;
use App\Models\Bbsetting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SpotSettingController extends Controller
{
    /**
     * Pair trading settings (ThinkPHP Trade/bbsetting GET).
     */
    public function show(): JsonResponse
    {
        $setting = Bbsetting::query()->find(1);

        return response()->json([
            'status' => true,
            'data' => $setting ? new SpotSettingResource($setting) : null,
        ]);
    }

    /**
     * Update pair trading settings (ThinkPHP Trade/bbsetting POST).
     */
    public function update(UpdateSpotSettingRequest $request): JsonResponse
    {
        $id = (int) $request->input('bbid', $request->input('id', 1));
        $payload = [
            'bb_kstime' => trim((string) $request->input('bb_kstime')),
        ];

        if ($id <= 0) {
            $setting = Bbsetting::query()->create($payload);
        } else {
            $setting = Bbsetting::query()->find($id);

            if (!$setting) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing params.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $updated = $setting->update($payload);

            if (!$updated) {
                return response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Success.',
            'data' => new SpotSettingResource($setting->fresh()),
        ]);
    }
}
