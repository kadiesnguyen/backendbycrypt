<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteConfigRequest;
use App\Http\Requests\Admin\UpdateSystemParamsRequest;
use App\Http\Resources\Admin\SiteConfigResource;
use App\Http\Resources\Admin\SystemParamsResource;
use App\Models\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteConfigController extends Controller
{
    /**
     * Basic website configuration (ThinkPHP Config/index GET).
     */
    public function show(): JsonResponse
    {
        $config = Config::query()->find(1);

        return response()->json([
            'status' => true,
            'data' => $config ? new SiteConfigResource($config) : null,
        ]);
    }

    /**
     * Update basic website configuration (ThinkPHP Config/edit POST).
     */
    public function update(UpdateSiteConfigRequest $request): JsonResponse
    {
        $config = Config::query()->find(1);

        if (!$config) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only([
            'webname',
            'webtitle',
            'bank_name',
            'bank_acc_no',
            'bank_acc_name',
            'weblogo',
            'waplogo',
            'websildea',
            'websildeb',
            'websildec',
            'wapsilded',
            'webissue',
            'webkj',
            'wapsildea',
            'wapsildeb',
            'wapsildec',
            'wapissue',
            'wapkj',
            'webtjimgs',
            'waptjimgs',
            'webswitch',
        ]);

        if (!$config->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot edit.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new SiteConfigResource($config->fresh()),
        ]);
    }

    /**
     * System parameter settings (ThinkPHP Config/qita GET).
     */
    public function showSystemParams(): JsonResponse
    {
        $config = Config::query()->find(1);

        return response()->json([
            'status' => true,
            'data' => $config ? new SystemParamsResource($config) : null,
        ]);
    }

    /**
     * Update system parameter settings (ThinkPHP Config/qitaEdit POST).
     */
    public function updateSystemParams(UpdateSystemParamsRequest $request): JsonResponse
    {
        $config = Config::query()->find(1);

        if (!$config) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only([
            'kefu',
            'appeal',
            'smsemail',
            'emailcode',
            'smstemple',
            'tgtext',
            'gfemail',
            'footertext',
            'telegram',
            'tymoney',
            'regswitch',
            'tbswitch',
            'regjl',
            'checkin_rewards',
            'checkin_notify_status',
            'checkin_notify',
        ]);

        if (array_key_exists('checkin_notify', $payload) && $payload['checkin_notify'] !== null) {
            $payload['checkin_notify'] = htmlspecialchars_decode((string) $payload['checkin_notify'], ENT_QUOTES);
        }

        if (!$config->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot edit.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new SystemParamsResource($config->fresh()),
        ]);
    }

    /**
     * Public asset upload for site config images (ThinkPHP Config/image).
     */
    public function uploadPublicImage(Request $request): JsonResponse
    {
        return $this->storeUploadedImage(
            $request,
            public_path('Upload/public'),
            plainPath: true
        );
    }

    /**
     * KindEditor image upload for config editor (ThinkPHP Config/uploadImage).
     */
    public function uploadEditorImage(Request $request): JsonResponse
    {
        $file = $request->file('imgFile') ?? $request->file('file');

        if (!$file) {
            return response()->json([
                'error' => 1,
                'message' => 'No file uploaded.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = validator(['file' => $file], [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png', 'max:5120'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => 1,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = public_path('Upload/config');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            return response()->json([
                'error' => 1,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return response()->json([
            'error' => 0,
            'url' => '/Upload/config/' . $filename,
        ]);
    }

    private function storeUploadedImage(Request $request, string $directory, bool $plainPath = false): JsonResponse
    {
        $file = $request->file('file')
            ?? $request->file('upload_file0')
            ?? collect($request->allFiles())->flatten()->first();

        if (!$file) {
            return response()->json([
                'status' => false,
                'message' => 'No file uploaded.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = validator(['file' => $file], [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png', 'max:3072'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        if ($plainPath) {
            return response()->json([
                'status' => true,
                'data' => [
                    'path' => $filename,
                ],
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'path' => $filename,
            ],
        ]);
    }
}
