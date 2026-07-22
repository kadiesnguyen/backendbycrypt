<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListPerpPositionsRequest;
use App\Http\Requests\Admin\SetPerpWinLossRequest;
use App\Http\Requests\Admin\UpdatePerpSettingsRequest;
use App\Http\Resources\Admin\PerpFillResource;
use App\Http\Resources\Admin\PerpPositionResource;
use App\Services\PerpAdminService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PerpPositionController extends Controller
{
    public function __construct(
        private readonly PerpAdminService $perpAdmin,
    ) {}

    public function index(ListPerpPositionsRequest $request): JsonResponse
    {
        $scope = (string) $request->input('scope', 'open');
        $perPage = (int) $request->input('per_page', 15);

        $paginator = $this->perpAdmin->listPositions(
            $scope,
            $request->input('username'),
            $request->input('symbol'),
            $perPage,
        );

        $items = collect($paginator->items())->map(function ($position) {
            return $this->perpAdmin->enrichWithMark($position);
        });

        return response()->json([
            'status' => true,
            'data' => PerpPositionResource::collection($items),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function fills(ListPerpPositionsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $paginator = $this->perpAdmin->listFills(
            $request->input('username'),
            $request->input('symbol'),
            $perPage,
        );

        return response()->json([
            'status' => true,
            'data' => PerpFillResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function pendingCount(): JsonResponse
    {
        $alert = $this->perpAdmin->pendingAlertData();

        $payload = [
            'status' => true,
            'data' => [
                'count' => $alert['count'],
                'has_new' => $alert['has_new'],
                'positions' => PerpPositionResource::collection(
                    collect($alert['positions'])->map(fn ($p) => $this->perpAdmin->enrichWithMark($p)),
                ),
            ],
        ];

        if ($alert['count'] > 0) {
            $payload['code'] = 1;
        }

        return response()->json($payload);
    }

    public function markNotified(): JsonResponse
    {
        $updated = $this->perpAdmin->markNotified();

        $payload = [
            'status' => true,
            'message' => 'Successfully.',
            'data' => ['updated' => $updated],
        ];

        if ($updated > 0) {
            $payload['code'] = 1;
        }

        return response()->json($payload);
    }

    public function setWinLoss(SetPerpWinLossRequest $request): JsonResponse
    {
        $result = $this->perpAdmin->setKongyk(
            (int) $request->input('id'),
            (int) $request->input('kongyk'),
        );

        $code = ($result['status'] ?? false) ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY;

        return response()->json($result, $code);
    }

    public function settle(int $id): JsonResponse
    {
        $result = $this->perpAdmin->settle($id);
        $code = ($result['status'] ?? false) ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY;

        return response()->json($result, $code);
    }

    public function settingsShow(): JsonResponse
    {
        return response()->json($this->perpAdmin->settingsPayload());
    }

    public function settingsUpdate(UpdatePerpSettingsRequest $request): JsonResponse
    {
        $rate = $this->perpAdmin->updateWinRate((float) $request->input('perp_win_rate'));

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => ['perp_win_rate' => $rate],
        ]);
    }
}
