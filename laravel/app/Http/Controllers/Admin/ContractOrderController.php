<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListContractOrdersRequest;
use App\Http\Requests\Admin\SetContractWinLossRequest;
use App\Http\Resources\Admin\ContractOrderResource;
use App\Models\Hyorder;
use App\Services\ContractOrderBalanceService;
use App\Services\HyorderSettlementService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ContractOrderController extends Controller
{
    public function index(ListContractOrdersRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');

        $query = Hyorder::query()
            ->where('status', 1)
            ->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        if ($request->filled('hyzd') && (int) $request->input('hyzd') > 0) {
            $query->where('hyzd', (int) $request->input('hyzd'));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => ContractOrderResource::collection(collect($paginator->items())),
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
        $query = Hyorder::query()
            ->where('status', 1)
            ->where('tznum', 0);

        $count = (clone $query)->count();

        $orders = (clone $query)
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $payload = [
            'status' => true,
            'data' => [
                'count' => $count,
                'has_new' => $count > 0,
                'orders' => ContractOrderResource::collection($orders),
            ],
        ];

        if ($count > 0) {
            $payload['code'] = 1;
        }

        return response()->json($payload);
    }

    public function markNotified(): JsonResponse
    {
        $updated = Hyorder::query()
            ->where('status', 1)
            ->where('tznum', 0)
            ->update(['tznum' => 1]);

        $payload = [
            'status' => true,
            'message' => 'Successfully.',
            'data' => [
                'updated' => $updated,
            ],
        ];

        if ($updated > 0) {
            $payload['code'] = 1;
        }

        return response()->json($payload);
    }

    public function setWinLoss(
        SetContractWinLossRequest $request,
        HyorderSettlementService $settlement,
    ): JsonResponse {
        $order = Hyorder::query()->find((int) $request->input('id'));

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $updated = $order->update([
            'kongyk' => (int) $request->input('kongyk'),
        ]);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $order->refresh();

        if ((int) $order->status === 1 && (int) $order->intselltime <= now()->timestamp + 10) {
            try {
                $settlement->settle($order);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage() ?: 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
        ]);
    }

    public function manualSettle(int $id, HyorderSettlementService $settlement): JsonResponse
    {
        $order = Hyorder::query()->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $order->status !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Order is not pending settlement.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $settlement->settle($order);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ?: 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order settled successfully.',
        ]);
    }

    public function settleStuck(HyorderSettlementService $settlement): JsonResponse
    {
        $result = $settlement->settleDueOrders();

        return response()->json([
            'status' => true,
            'message' => $result['settled'] > 0
                ? "Settled {$result['settled']} stuck order(s)."
                : 'No overdue orders to settle.',
            'data' => $result,
        ]);
    }

    public function closed(
        ListContractOrdersRequest $request,
        ContractOrderBalanceService $balances,
    ): JsonResponse {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');
        $invit = $request->input('invit');

        $query = Hyorder::query()
            ->where('status', 2)
            ->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        if ($invit !== null && $invit !== '') {
            $query->where('invit', $invit);
        }

        $paginator = $query->paginate($perPage);
        $items = collect($paginator->items());
        $balanceMap = $balances->forOrders($items);

        $items->each(function (Hyorder $order) use ($balanceMap): void {
            $balance = $balanceMap[(int) $order->id] ?? [];

            $order->setAttribute('balance_before', $balance['balance_before'] ?? null);
            $order->setAttribute('balance_after', $balance['balance_after'] ?? null);
            $order->setAttribute('profit_loss', $balance['profit_loss'] ?? null);
        });

        return response()->json([
            'status' => true,
            'data' => ContractOrderResource::collection($items),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
