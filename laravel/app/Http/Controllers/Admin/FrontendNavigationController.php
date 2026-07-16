<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListFrontendNavigationRequest;
use App\Http\Requests\Admin\UpdateFrontendNavigationStatusRequest;
use App\Http\Requests\Admin\UpsertFrontendNavigationRequest;
use App\Http\Resources\Admin\FrontendNavigationResource;
use App\Models\Daohang;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class FrontendNavigationController extends Controller
{
    /**
     * List frontend navigation links (ThinkPHP Config/daohang).
     */
    public function index(ListFrontendNavigationRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $field = $request->input('field');
        $name = $request->input('name');

        $query = Daohang::query()->orderBy('sort');

        if ($field && $name) {
            if ($field === 'username') {
                $userId = User::query()->where('username', $name)->value('id');
                $query->where('userid', $userId);
            } elseif ($field === 'title') {
                $query->where('title', 'like', '%' . $name . '%');
            } else {
                $query->where($field, $name);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status') - 1);
        }

        if ($request->filled('lang')) {
            $query->where('lang', $request->input('lang'));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => FrontendNavigationResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Form metadata for create/edit (ThinkPHP Config/daohangEdit GET __LANG__).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a navigation link for editing (ThinkPHP Config/daohangEdit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $item = Daohang::query()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new FrontendNavigationResource($item),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a navigation link (ThinkPHP Config/daohangEdit POST without id).
     */
    public function store(UpsertFrontendNavigationRequest $request): JsonResponse
    {
        $payload = $this->navigationPayload($request);
        $payload['addtime'] = time();

        $item = Daohang::query()->create($payload);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->syncCloseUrl($request->input('url'), (bool) $request->input('get_login'));

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new FrontendNavigationResource($item),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a navigation link (ThinkPHP Config/daohangEdit POST with id).
     */
    public function update(UpsertFrontendNavigationRequest $request, int $id): JsonResponse
    {
        $item = Daohang::query()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$item->update($this->navigationPayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->syncCloseUrl($request->input('url'), (bool) $request->input('get_login'));

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new FrontendNavigationResource($item->fresh()),
        ]);
    }

    /**
     * Bulk status change for navigation links (ThinkPHP Config/daohangStatus).
     */
    public function updateStatus(UpdateFrontendNavigationStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Daohang::query()->whereIn('id', $ids);

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
            'repeal' => ['status' => 2, 'endtime' => time()],
            'del' => ['status' => -1],
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
        return [
            'languages' => [
                ['value' => 'vi-vn', 'label' => 'Tiếng Việt'],
                ['value' => 'zh-cn', 'label' => 'Tiếng Trung'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function navigationPayload(UpsertFrontendNavigationRequest $request): array
    {
        return $request->only([
            'lang',
            'name',
            'title',
            'url',
            'sort',
            'status',
            'get_login',
            'access',
        ]);
    }

    private function syncCloseUrl(?string $url, bool $requiresLogin): void
    {
        if ($url === null || $url === '') {
            return;
        }

        $closeUrl = Cache::get('closeUrl', []);
        if (!is_array($closeUrl)) {
            $closeUrl = [];
        }

        if ($requiresLogin) {
            $closeUrl[] = $url;
        } else {
            $key = array_search($url, $closeUrl, true);
            if ($key !== false) {
                unset($closeUrl[$key]);
            }
        }

        $closeUrl = array_values(array_unique($closeUrl));
        sort($closeUrl);
        Cache::forever('closeUrl', $closeUrl);
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
