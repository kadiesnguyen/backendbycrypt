<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListCoinsRequest;
use App\Http\Requests\Admin\UpdateCoinStatusRequest;
use App\Http\Requests\Admin\UpsertCoinRequest;
use App\Http\Resources\Admin\CoinResource;
use App\Models\Coin;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CoinController extends Controller
{
    /**
     * List coins (ThinkPHP Config/coin).
     */
    public function index(ListCoinsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 100);
        $field = $request->input('field');
        $name = $request->input('name');

        $query = Coin::query()->orderBy('sort');

        if ($field && $name) {
            if ($field === 'username') {
                $userId = User::query()->where('username', $name)->value('id');
                $query->where('userid', $userId);
            } else {
                $query->where($field, $name);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status') - 1);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => CoinResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Show a coin for editing (ThinkPHP Config/coinEdit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $coin = Coin::query()->find($id);

        if (!$coin) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new CoinResource($coin),
        ]);
    }

    /**
     * Create a coin (ThinkPHP Config/coinEdit POST without id).
     */
    public function store(UpsertCoinRequest $request): JsonResponse
    {
        $name = strtolower(trim((string) $request->input('name')));

        if (preg_match('/^[a-zA-Z]{1}[0-9a-zA-Z_]{5,15}$/', $name)) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect currency name format.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Coin::query()->where('name', $name)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Currency exists.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::statement(
                'ALTER TABLE `tw_user_coin` ADD `' . $name . '` DECIMAL(20,10) UNSIGNED NOT NULL DEFAULT 0.00'
            );
            DB::statement(
                'ALTER TABLE `tw_user_coin` ADD `' . $name . 'd` DECIMAL(20,10) UNSIGNED NOT NULL DEFAULT 0.00'
            );
        } catch (\Throwable) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $payload = $this->coinPayload($request);
        $payload['name'] = $name;
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        $coin = Coin::query()->create($payload);

        if (!$coin) {
            return response()->json([
                'status' => false,
                'message' => 'Data is not modified.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new CoinResource($coin),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a coin (ThinkPHP Config/coinEdit POST with id).
     */
    public function update(UpsertCoinRequest $request, int $id): JsonResponse
    {
        $coin = Coin::query()->find($id);

        if (!$coin) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $this->coinPayload($request);
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        if (!$coin->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Data is not modified.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new CoinResource($coin->fresh()),
        ]);
    }

    /**
     * Bulk status change for coins (ThinkPHP Config/coinStatus).
     */
    public function updateStatus(UpdateCoinStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Please select data to operate.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Coin::query()->whereIn('id', $ids);

        if ($type === 'delt') {
            $coins = $query->get(['id', 'name']);

            foreach ($coins as $coin) {
                try {
                    DB::statement('ALTER TABLE `tw_user_coin` DROP COLUMN `' . $coin->name . '`');
                } catch (\Throwable) {
                    // Column may already be absent; continue with coin deletion.
                }
            }

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
                'message' => 'Invalid parameter.',
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
     * Coin icon upload (ThinkPHP Config/coinImage).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $file = $request->file('file')
            ?? collect($request->allFiles())->flatten()->first();

        if (!$file) {
            return response()->json([
                'status' => false,
                'message' => 'No file uploaded.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = validator(['file' => $file], [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png', 'max:3072'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = public_path('Upload/coin');

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
    private function coinPayload(UpsertCoinRequest $request): array
    {
        return $request->only([
            'name',
            'title',
            'type',
            'czline',
            'czaddress',
            'czstatus',
            'czminnum',
            'txstatus',
            'txminnum',
            'txmaxnum',
            'sxftype',
            'txsxf',
            'txsxf_n',
            'bbsxf',
            'hysxf',
            'bank',
            'sort',
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
