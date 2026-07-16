<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListTradingMarketsRequest;
use App\Http\Requests\Admin\UpsertTradingMarketRequest;
use App\Http\Resources\Admin\TradingMarketResource;
use App\Models\Coin;
use App\Models\Market;
use App\Models\User;
use App\Support\CoreConfig;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TradingMarketController extends Controller
{
    /**
     * List trading markets (ThinkPHP Trade/market).
     */
    public function index(ListTradingMarketsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $field = $request->input('field');
        $name = $request->input('name');

        $query = Market::query()->orderByDesc('id');

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
            'data' => TradingMarketResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Form metadata for create/edit (ThinkPHP Trade/marketEdit GET extras).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a trading market for editing (ThinkPHP Trade/marketEdit GET).
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
            'data' => new TradingMarketResource($market),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a trading market (ThinkPHP Trade/marketEdit POST without id).
     */
    public function store(UpsertTradingMarketRequest $request): JsonResponse
    {
        $name = trim((string) $request->input('sellname')) . '_' . trim((string) $request->input('buyname'));

        if (Market::query()->where('name', $name)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'The market exists.',
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
            'data' => new TradingMarketResource($market->fresh()),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a trading market (ThinkPHP Trade/marketEdit POST with id).
     */
    public function update(UpsertTradingMarketRequest $request, int $id): JsonResponse
    {
        $market = Market::query()->find($id);

        if (!$market) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $this->marketPayload($request);
        $updated = $market->update($payload);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new TradingMarketResource($market->fresh()),
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

        $coins = Coin::query()
            ->orderBy('sort')
            ->get(['name', 'title'])
            ->map(fn (Coin $coin) => [
                'name' => $coin->name,
                'title' => $coin->title,
            ])
            ->values()
            ->all();

        return [
            'trading_areas' => $tradingAreas,
            'coins' => $coins,
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
    private function marketPayload(UpsertTradingMarketRequest $request): array
    {
        return $request->only([
            'jiaoyiqu',
            'round',
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
            'start_time',
            'start_minute',
            'stop_time',
            'stop_minute',
            'agree6',
            'agree7',
            'trade',
            'status',
            'shuadan',
            'sdtype',
            'sdhigh',
            'sdlow',
        ]);
    }
}
