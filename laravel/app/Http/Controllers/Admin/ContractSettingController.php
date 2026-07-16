<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContractSettingRequest;
use App\Http\Resources\Admin\ContractSettingResource;
use App\Models\Hysetting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ContractSettingController extends Controller
{
    /**
     * Contract trading settings (ThinkPHP Trade/sethy GET).
     */
    public function show(): JsonResponse
    {
        $setting = Hysetting::query()->find(1);

        return response()->json([
            'status' => true,
            'data' => $setting ? new ContractSettingResource($setting) : null,
        ]);
    }

    /**
     * Update contract trading settings (ThinkPHP Trade/sethy POST).
     */
    public function update(UpdateContractSettingRequest $request): JsonResponse
    {
        $id = (int) $request->input('hy_id', $request->input('id', 1));
        $payload = $request->only([
            'hy_sxf',
            'hy_time',
            'hy_ykbl',
            'hy_tzed',
            'hy_min',
            'hy_min_per_frame',
            'hy_max_per_frame',
            'hy_kstime',
            'hy_ksid',
            'hy_ylid',
            'hy_fkgl',
        ]);

        if ($id <= 0) {
            $setting = Hysetting::query()->create($payload);
        } else {
            $setting = Hysetting::query()->find($id);

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
            'data' => new ContractSettingResource($setting->fresh()),
        ]);
    }
}
