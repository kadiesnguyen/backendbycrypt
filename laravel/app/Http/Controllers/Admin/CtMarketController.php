<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListCtMarketsRequest;
use App\Http\Requests\Admin\UpdateCtMarketStatusRequest;
use App\Http\Requests\Admin\UpsertCtMarketRequest;
use App\Http\Resources\Admin\CtMarketResource;
use App\Models\Ctmarket;
use App\Support\TradingSymbol;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CtMarketController extends Controller
{
    /**
     * List contract markets (ThinkPHP Config/ctmarket).
     */
    public function index(ListCtMarketsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 100);

        $paginator = Ctmarket::query()
            ->orderBy('sort')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => CtMarketResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Show a contract market for editing (ThinkPHP Config/ctmarketEdit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $market = Ctmarket::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new CtMarketResource($market),
        ]);
    }

    /**
     * Create a contract market (ThinkPHP Config/ctmarketEdit POST without id).
     */
    public function store(UpsertCtMarketRequest $request): JsonResponse
    {
        $market = Ctmarket::query()->create($this->marketPayload($request));

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new CtMarketResource($market),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a contract market (ThinkPHP Config/ctmarketEdit POST with id).
     */
    public function update(UpsertCtMarketRequest $request, int $id): JsonResponse
    {
        $market = Ctmarket::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$market->update($this->marketPayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new CtMarketResource($market->fresh()),
        ]);
    }

    /**
     * Bulk status change for contract markets (ThinkPHP Config/ctmarketoStatus).
     */
    public function updateStatus(UpdateCtMarketStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Ctmarket::query()->whereIn('id', $ids);

        if ($type === 'del') {
            if (!$query->delete()) {
                return response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully.',
            ]);
        }

        $data = match ($type) {
            'forbid' => ['status' => 2],
            'resume' => ['status' => 1],
            default => null,
        };

        if ($data === null) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$query->update($data)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function marketPayload(UpsertCtMarketRequest $request): array
    {
        $coinname = strtolower(trim((string) $request->input('coinname')));
        $symbol = TradingSymbol::normalize($coinname . 'usdt');

        return [
            'coinname' => $coinname,
            'name' => strtolower($symbol),
            'symbol' => $symbol,
            'title' => strtoupper($coinname) . '/USDT',
            'status' => (int) $request->input('status'),
            'state' => (int) $request->input('state'),
            'sort' => (int) $request->input('sort'),
            'addtime' => now(),
        ];
    }

    /**
     * @return list<int>
     */
    private function normalizeIds(mixed $ids): array
    {
        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids)));
        }

        if (!is_array($ids)) {
            return [];
        }

        return array_values(array_filter(array_map('intval', $ids), fn (int $id) => $id > 0));
    }

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator<mixed> $paginator
     * @return array<string, int>
     */
    private function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}
