<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListFooterNavigationRequest;
use App\Http\Requests\Admin\UpdateFooterNavigationStatusRequest;
use App\Http\Requests\Admin\UpsertFooterNavigationRequest;
use App\Http\Resources\Admin\FooterNavigationResource;
use App\Models\Footer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class FooterNavigationController extends Controller
{
    /**
     * List footer navigation links (ThinkPHP Config/dhfooter).
     */
    public function index(ListFooterNavigationRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $field = $request->input('field');
        $name = $request->input('name');

        $query = Footer::query()->orderBy('sort');

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

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => FooterNavigationResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Form metadata for create/edit.
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a footer link for editing (ThinkPHP Config/dhfooterEdit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $item = Footer::query()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new FooterNavigationResource($item),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a footer link (ThinkPHP Config/dhfooterEdit POST without id).
     */
    public function store(UpsertFooterNavigationRequest $request): JsonResponse
    {
        $payload = $this->navigationPayload($request);
        $payload['addtime'] = time();

        $item = Footer::query()->create($payload);

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
            'data' => new FooterNavigationResource($item),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a footer link (ThinkPHP Config/dhfooterEdit POST with id).
     */
    public function update(UpsertFooterNavigationRequest $request, int $id): JsonResponse
    {
        $item = Footer::query()->find($id);

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
            'data' => new FooterNavigationResource($item->fresh()),
        ]);
    }

    /**
     * Bulk status change for footer links (ThinkPHP Config/dhfooterStatus).
     */
    public function updateStatus(UpdateFooterNavigationStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Footer::query()->whereIn('id', $ids);

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
    private function navigationPayload(UpsertFooterNavigationRequest $request): array
    {
        return $request->only([
            'lang',
            'name',
            'title',
            'url',
            'img',
            'type',
            'remark',
            'sort',
            'status',
            'get_login',
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
