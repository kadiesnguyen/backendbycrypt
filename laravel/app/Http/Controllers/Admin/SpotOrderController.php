<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListSpotOrdersRequest;
use App\Http\Resources\Admin\SpotOrderResource;
use App\Models\Bborder;
use Illuminate\Http\JsonResponse;

class SpotOrderController extends Controller
{
    /**
     * Market spot orders (ThinkPHP Trade/bbsjlist, ordertype=2).
     */
    public function marketOrders(ListSpotOrdersRequest $request): JsonResponse
    {
        return $this->paginatedOrders($request, 2);
    }

    /**
     * Limit spot orders (ThinkPHP Trade/bbxjlist, ordertype=1).
     */
    public function limitOrders(ListSpotOrdersRequest $request): JsonResponse
    {
        return $this->paginatedOrders($request, 1);
    }

    private function paginatedOrders(ListSpotOrdersRequest $request, int $ordertype): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $query = Bborder::query()
            ->where('ordertype', $ordertype)
            ->orderByDesc('id');

        if ($request->filled('type') && (int) $request->input('type') > 0) {
            $query->where('type', (int) $request->input('type'));
        }

        if ($request->filled('status') && (int) $request->input('status') > 0) {
            $query->where('status', (int) $request->input('status'));
        }

        $username = $request->input('username');
        if ($username !== null && $username !== '') {
            $query->where('account', $username);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => SpotOrderResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
