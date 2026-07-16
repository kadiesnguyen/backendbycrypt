<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListMiningPoolsRequest;
use App\Http\Requests\Admin\UpsertMiningPoolRequest;
use App\Http\Resources\Admin\MiningPoolResource;
use App\Models\Coin;
use App\Models\Orepool;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MiningPoolController extends Controller
{
    /**
     * List mining pools (ThinkPHP Orepool/index view data).
     */
    public function index(ListMiningPoolsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $paginator = Orepool::query()
            ->orderBy('sort')
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => MiningPoolResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Form metadata for create (ThinkPHP Orepool/addorepool).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a mining pool for editing (ThinkPHP Orepool/editorepool).
     */
    public function show(int $id): JsonResponse
    {
        $pool = Orepool::query()->find($id);

        if (!$pool) {
            return response()->json([
                'status' => false,
                'message' => 'No records found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new MiningPoolResource($pool),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a mining pool (ThinkPHP Orepool/orepoolsave).
     */
    public function store(UpsertMiningPoolRequest $request): JsonResponse
    {
        $payload = $this->poolPayload($request);
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        $pool = Orepool::query()->create($payload);

        if (!$pool) {
            return response()->json([
                'status' => false,
                'message' => 'Add unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Add successfully.',
            'data' => new MiningPoolResource($pool),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a mining pool (ThinkPHP Orepool/orepoolesave).
     */
    public function update(UpsertMiningPoolRequest $request, int $id): JsonResponse
    {
        $pool = Orepool::query()->find($id);

        if (!$pool) {
            return response()->json([
                'status' => false,
                'message' => 'No records found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $this->poolPayload($request);
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        if (!$pool->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new MiningPoolResource($pool->fresh()),
        ]);
    }

    /**
     * Delete a mining pool (ThinkPHP Orepool/delore).
     */
    public function destroy(int $id): JsonResponse
    {
        $pool = Orepool::query()->find($id);

        if (!$pool) {
            return response()->json([
                'status' => false,
                'message' => 'No records found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((float) $pool->allmoney > 0) {
            return response()->json([
                'status' => false,
                'message' => 'There is already a member in this mining pool and cannot be removed.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$pool->delete()) {
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
        $coins = Coin::query()
            ->where('status', 1)
            ->orderByDesc('id')
            ->get(['name', 'title'])
            ->map(fn (Coin $coin) => [
                'name' => $coin->name,
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
    private function poolPayload(UpsertMiningPoolRequest $request): array
    {
        $oreimg = trim((string) $request->input('oreimg', $request->input('idimg1')));

        return [
            'oretitle' => trim((string) $request->input('oretitle')),
            'oreimg' => $oreimg,
            'summoney' => trim((string) $request->input('summoney')),
            'fmoney' => trim((string) $request->input('fmoney')),
            'minmoney' => trim((string) $request->input('minmoney')),
            'maxmoney' => trim((string) $request->input('maxmoney')),
            'coinname' => trim((string) $request->input('coinname')),
            'cc_coin' => trim((string) $request->input('cc_coin')),
            'rtype' => trim((string) $request->input('rtype')),
            'status' => trim((string) $request->input('status')),
            'buytype' => trim((string) $request->input('buytype')),
            'arrmoney' => $request->input('arrmoney', 0),
            'buynum' => trim((string) $request->input('buynum')),
            'rway' => trim((string) $request->input('rway')),
            'sfbl' => trim((string) $request->input('sfbl')),
            'gdnum' => trim((string) $request->input('gdnum')),
            'gdbl' => trim((string) $request->input('gdbl')),
            'sort' => trim((string) $request->input('sort')),
        ];
    }
}
