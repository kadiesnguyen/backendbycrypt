<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListPlatformMarketsRequest;
use App\Http\Requests\Admin\UpdatePlatformMarketQuotesRequest;
use App\Http\Requests\Admin\UpdatePlatformMarketRobotRequest;
use App\Http\Requests\Admin\UpdatePlatformMarketStatusRequest;
use App\Http\Requests\Admin\UpsertPlatformMarketRequest;
use App\Http\Resources\Admin\PlatformMarketResource;
use App\Models\Market;
use App\Models\User;
use App\Support\CoreConfig;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlatformMarketController extends Controller
{
    /**
     * List platform markets (ThinkPHP Config/marketo).
     */
    public function index(ListPlatformMarketsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 100);
        $field = $request->input('field');
        $name = $request->input('name');

        $query = Market::query()->orderBy('sort');

        if ($field && $name) {
            if ($field === 'username') {
                $userId = User::query()->where('username', $name)->value('id');
                $query->where('userid', $userId);
            } else {
                $query->where($field, $name);
            }
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => PlatformMarketResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Form metadata for create/edit (ThinkPHP Config/marketoEdit GET extras).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a platform market for editing (ThinkPHP Config/marketoEdit GET).
     */
    public function show(int $id): JsonResponse
    {
        $market = Market::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new PlatformMarketResource($market),
            'meta' => array_merge($this->buildFormMeta(), [
                'robot_min_quantity' => $this->robotMinQuantity($market->round),
            ]),
        ]);
    }

    /**
     * Create a platform market (ThinkPHP Config/marketoEdit POST without id).
     */
    public function store(UpsertPlatformMarketRequest $request): JsonResponse
    {
        $categories = CoreConfig::indexCategories();
        if ($categories === []) {
            return response()->json([
                'status' => false,
                'message' => 'Configuration error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $buyname = trim((string) $request->input('buyname'));
        $sellname = trim((string) $request->input('sellname'));
        $name = $sellname . '_' . $buyname;

        if (Market::query()->where('name', $name)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'The market exists.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $jiaoyiqu = (int) $request->input('jiaoyiqu');
        $expectedBuy = strtolower($categories[$jiaoyiqu] ?? '');

        if ($buyname !== $expectedBuy) {
            return response()->json([
                'status' => false,
                'message' => "The trading region it belongs to does not match the buyer's currency{$buyname}.",
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $payload = $this->marketPayload($request);
        $payload['name'] = $name;
        $payload['addtime'] = time();

        $market = Market::query()->create($payload);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new PlatformMarketResource($market),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a platform market (ThinkPHP Config/marketoEdit POST with id).
     */
    public function update(UpsertPlatformMarketRequest $request, int $id): JsonResponse
    {
        $market = Market::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $this->marketPayload($request);

        if (!$market->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new PlatformMarketResource($market->fresh()),
        ]);
    }

    /**
     * Update market quote data (ThinkPHP Config/marketoEdit2 POST).
     */
    public function updateQuotes(UpdatePlatformMarketQuotesRequest $request, int $id): JsonResponse
    {
        $market = Market::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only([
            'faxingjia',
            'new_price',
            'buy_price',
            'sell_price',
            'min_price',
            'max_price',
            'volume',
            'change',
            'hou_price',
        ]);

        if (!$market->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new PlatformMarketResource($market->fresh()),
        ]);
    }

    /**
     * Update trading robot settings (ThinkPHP Config/marketoEdit3 POST).
     */
    public function updateRobot(UpdatePlatformMarketRobotRequest $request, int $id): JsonResponse
    {
        $market = Market::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only([
            'shuadan',
            'sdhigh',
            'sdlow',
            'sdhigh_num',
            'sdlow_num',
            'round',
        ]);

        if (!$market->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new PlatformMarketResource($market->fresh()),
            'meta' => [
                'robot_min_quantity' => $this->robotMinQuantity($market->fresh()->round),
            ],
        ]);
    }

    /**
     * Bulk status change for platform markets (ThinkPHP Config/marketoStatus).
     */
    public function updateStatus(UpdatePlatformMarketStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Market::query()->whereIn('id', $ids);

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
            'forbid' => ['status' => 0],
            'resume' => ['status' => 1],
            'repeal' => ['status' => 2, 'endtime' => time()],
            'delete' => ['status' => -1],
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
    private function buildFormMeta(): array
    {
        $categories = CoreConfig::indexCategories();
        $tradingAreas = [];

        foreach ($categories as $index => $label) {
            $tradingAreas[] = [
                'value' => $index,
                'label' => $label,
            ];
        }

        return [
            'trading_areas' => $tradingAreas,
            'hours' => array_map(
                fn (int $hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT),
                range(0, 23)
            ),
            'minutes' => array_map(
                fn (int $minute) => str_pad((string) $minute, 2, '0', STR_PAD_LEFT),
                range(0, 59)
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function marketPayload(UpsertPlatformMarketRequest $request): array
    {
        $payload = $request->only([
            'jiaoyiqu',
            'round',
            'round_mum',
            'fee_buy',
            'fee_sell',
            'buy_min',
            'buy_max',
            'sell_min',
            'sell_max',
            'trade_min',
            'trade_max',
            'trade_buy_num_min',
            'trade_buy_num_max',
            'trade_sell_num_min',
            'trade_sell_num_max',
            'invit_1',
            'invit_2',
            'invit_3',
            'invit_buy',
            'invit_sell',
            'zhang',
            'die',
            'hou_price',
            'trade',
            'status',
            'sort',
            'start_time',
            'start_minute',
            'stop_time',
            'stop_minute',
            'agree6',
            'agree7',
        ]);

        if (!$request->filled('hou_price')) {
            $payload['hou_price'] = '0.00';
        }

        return $payload;
    }

    private function robotMinQuantity(mixed $round): string
    {
        $decimals = max(0, (int) $round - 1);

        return number_format(0, $decimals, '.', '') . '1';
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
