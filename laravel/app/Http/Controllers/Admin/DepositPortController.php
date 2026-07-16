<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateDepositPortStatusRequest;
use App\Http\Requests\Admin\UpsertDepositPortRequest;
use App\Http\Resources\Admin\CoinResource;
use App\Http\Resources\Admin\DepositPortResource;
use App\Models\Coin;
use App\Models\RechargeMethod;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DepositPortController extends Controller
{
    /**
     * List deposit ports (ThinkPHP Config/depositport).
     */
    public function index(): JsonResponse
    {
        $ports = RechargeMethod::query()->orderBy('id')->get();

        return response()->json([
            'status' => true,
            'data' => DepositPortResource::collection($ports),
        ]);
    }

    /**
     * Form metadata for create/edit (ThinkPHP Config/depositportEdit GET coin_list).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a deposit port for editing (ThinkPHP Config/depositportEdit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $port = RechargeMethod::query()->find($id);

        if (!$port) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new DepositPortResource($port),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a deposit port (ThinkPHP Config/depositportEdit POST without id).
     */
    public function store(UpsertDepositPortRequest $request): JsonResponse
    {
        $port = RechargeMethod::query()->create($this->portPayload($request));

        if (!$port) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new DepositPortResource($port),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a deposit port (ThinkPHP Config/depositportEdit POST with id).
     */
    public function update(UpsertDepositPortRequest $request, int $id): JsonResponse
    {
        $port = RechargeMethod::query()->find($id);

        if (!$port) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$port->update($this->portPayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new DepositPortResource($port->fresh()),
        ]);
    }

    /**
     * Bulk status change for deposit ports (ThinkPHP Config/depositportStatus).
     */
    public function updateStatus(UpdateDepositPortStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = RechargeMethod::query()->whereIn('id', $ids);

        if ($type === 'delete') {
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
        $coins = Coin::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['id', 'name', 'title']);

        return [
            'coins' => CoinResource::collection($coins),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function portPayload(UpsertDepositPortRequest $request): array
    {
        return $request->only([
            'name',
            'wallet',
            'address',
            'coin',
            'status',
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
}
