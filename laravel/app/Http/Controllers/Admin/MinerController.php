<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListFrozenProfitsRequest;
use App\Http\Requests\Admin\ListMinerOrdersRequest;
use App\Http\Requests\Admin\ListMinerProfitsRequest;
use App\Http\Requests\Admin\ListMinersRequest;
use App\Http\Requests\Admin\UpdateMinerOrderStatusRequest;
use App\Http\Requests\Admin\UpdateMinerStatusRequest;
use App\Http\Requests\Admin\UpsertMinerRequest;
use App\Http\Resources\Admin\FrozenProfitResource;
use App\Http\Resources\Admin\MinerOrderResource;
use App\Http\Resources\Admin\MinerProfitResource;
use App\Http\Resources\Admin\MinerResource;
use App\Models\Coin;
use App\Models\Djprofit;
use App\Models\Kjorder;
use App\Models\Kjprofit;
use App\Models\Kuangji;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinerController extends Controller
{
    /**
     * List mining machines (ThinkPHP Kuangm/index).
     */
    public function index(ListMinersRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $paginator = Kuangji::query()
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => MinerResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Form metadata for create (ThinkPHP Kuangm/addkuangj GET without id).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a mining machine for editing (ThinkPHP Kuangm/addkuangj GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $miner = Kuangji::query()->find($id);

        if (!$miner) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new MinerResource($miner),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a mining machine (ThinkPHP Kuangm/addkj POST without kid).
     */
    public function store(UpsertMinerRequest $request): JsonResponse
    {
        $payload = $this->minerPayload($request);
        $payload['status'] = 1;
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        $miner = Kuangji::query()->create($payload);

        if (!$miner) {
            return response()->json([
                'status' => false,
                'message' => 'Add unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Add successfully.',
            'data' => new MinerResource($miner),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a mining machine (ThinkPHP Kuangm/addkj POST with kid).
     */
    public function update(UpsertMinerRequest $request, int $id): JsonResponse
    {
        $miner = Kuangji::query()->find($id);

        if (!$miner) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $this->minerPayload($request);
        $payload['status'] = 1;

        if (!$miner->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new MinerResource($miner->fresh()),
        ]);
    }

    /**
     * Bulk status change for mining machines (ThinkPHP Kuangm/kuangjStatus).
     */
    public function updateStatus(UpdateMinerStatusRequest $request): JsonResponse
    {
        return $this->applyStatusMutation(
            Kuangji::query(),
            $this->normalizeIds($request->input('ids')),
            (string) $request->input('type')
        );
    }

    /**
     * List active miner orders (ThinkPHP Kuangm/kjlist).
     */
    public function orders(ListMinerOrdersRequest $request): JsonResponse
    {
        return $this->paginateOrders($request, fn ($query) => $query->where('status', '<', 3));
    }

    /**
     * List expired miner orders (ThinkPHP Kuangm/overlist).
     */
    public function expiredOrders(ListMinerOrdersRequest $request): JsonResponse
    {
        return $this->paginateOrders($request, fn ($query) => $query->where('status', 3));
    }

    /**
     * Bulk status change for miner orders (ThinkPHP Kuangm/userkjStatus).
     */
    public function updateOrderStatus(UpdateMinerOrderStatusRequest $request): JsonResponse
    {
        return $this->applyStatusMutation(
            Kjorder::query(),
            $this->normalizeIds($request->input('ids')),
            (string) $request->input('type')
        );
    }

    /**
     * List miner income records (ThinkPHP Kuangm/kjsylist).
     */
    public function profits(ListMinerProfitsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');

        $query = Kjprofit::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', trim((string) $username));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => MinerProfitResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * List frozen income records (ThinkPHP Kuangm/djprofit).
     */
    public function frozenProfits(ListFrozenProfitsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');

        $query = Djprofit::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', trim((string) $username));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => FrozenProfitResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Upload mining machine image (ThinkPHP Kuangm/image).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'image', 'max:3072'],
        ]);

        $file = $validated['file'];
        $directory = public_path('Upload/public');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return response()->json([
            'status' => true,
            'data' => [
                'path' => $filename,
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildFormMeta(): array
    {
        $coins = Coin::query()
            ->where('status', 1)
            ->orderByDesc('id')
            ->get(['id', 'name', 'title'])
            ->map(fn (Coin $coin) => [
                'id' => $coin->id,
                'name' => trim((string) $coin->name),
                'title' => $coin->title,
            ])
            ->values()
            ->all();

        return [
            'coins' => $coins,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function minerPayload(UpsertMinerRequest $request): array
    {
        $type = (int) $request->input('type');

        $payload = [
            'title' => trim((string) $request->input('title')),
            'rtype' => trim((string) $request->input('rtype')),
            'type' => $type,
            'content' => trim((string) $request->input('content', '')),
            'imgs' => trim((string) $request->input('imgs', '')),
            'dayoutnum' => trim((string) $request->input('dayoutnum')),
            'outtype' => trim((string) $request->input('outtype')),
            'outcoin' => trim((string) $request->input('outcoin')),
            'pricenum' => trim((string) $request->input('pricenum')),
            'pricecoin' => trim((string) $request->input('pricecoin')),
            'buymax' => trim((string) $request->input('buymax')),
            'cycle' => trim((string) $request->input('cycle')),
            'suanl' => trim((string) $request->input('suanl')),
            'allnum' => trim((string) $request->input('allnum')),
            'ycnum' => trim((string) $request->input('ycnum')),
            'jlnum' => trim((string) $request->input('jlnum')),
            'jlcoin' => trim((string) $request->input('jlcoin')),
            'buyask' => trim((string) $request->input('buyask')),
            'asknum' => trim((string) $request->input('asknum')),
            'djout' => trim((string) $request->input('djout')),
            'djday' => trim((string) $request->input('djday', 0)),
        ];

        if ($type === 1) {
            $payload['sharebl'] = 0;
            $payload['sharecode'] = '';
        } elseif ($type === 2) {
            $payload['sharebl'] = trim((string) $request->input('sharebl', ''));
            $payload['sharecode'] = $this->createShareCode();
        }

        return $payload;
    }

    private function paginateOrders(ListMinerOrdersRequest $request, callable $scope): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');

        $query = Kjorder::query()->orderByDesc('id');
        $scope($query);

        if ($username !== null && $username !== '') {
            $query->where('username', trim((string) $username));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => MinerOrderResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder<Kuangji|Kjorder> $query
     */
    private function applyStatusMutation($query, array $ids, string $type): JsonResponse
    {
        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $scopedQuery = (clone $query)->whereIn('id', $ids);

        $result = match ($type) {
            '1' => $scopedQuery->update(['status' => 1]),
            '2' => $scopedQuery->update(['status' => 2]),
            '3' => $scopedQuery->delete(),
            default => false,
        };

        if ($result === false) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$result) {
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

    private function createShareCode(int $length = 13): string
    {
        $chars = 'ABDEFGHJKLMNPQRSTVWXYabdefghijkmnpqrstvwxy23456789';
        $result = '';

        while (strlen($result) < $length) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $result;
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
