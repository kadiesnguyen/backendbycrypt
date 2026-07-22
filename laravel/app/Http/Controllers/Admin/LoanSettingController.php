<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLoanSettingsRequest;
use App\Http\Resources\Admin\LoanSettingResource;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class LoanSettingController extends Controller
{
    public function __construct(private readonly LoanService $loans)
    {
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => new LoanSettingResource($this->loans->settings()),
        ]);
    }

    public function update(UpdateLoanSettingsRequest $request): JsonResponse
    {
        $settings = $this->loans->updateSettings($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new LoanSettingResource($settings),
        ]);
    }
}
