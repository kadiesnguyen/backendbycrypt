<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListAdminsRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminStatusRequest;
use App\Http\Resources\Admin\AdminAccountResource;
use App\Models\Admin;
use App\Models\AuthGroup;
use App\Models\AuthGroupAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function index(ListAdminsRequest $request): JsonResponse
    {
        /** @var Admin $currentAdmin */
        $currentAdmin = auth('admin')->user();
        $perPage = (int) $request->input('per_page', 15);

        $query = Admin::query()->orderByDesc('id');

        if ($request->filled('field') && $request->filled('name')) {
            $query->where($request->input('field'), $request->input('name'));
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status') - 1);
        }

        if ($currentAdmin->username) {
            $query->where('username', '!=', $currentAdmin->username);
        }

        $paginator = $query->paginate($perPage);
        $admins = collect($paginator->items());
        $this->enrichAdmins($admins);

        return response()->json([
            'status' => true,
            'data' => AdminAccountResource::collection($admins),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        /** @var Admin $currentAdmin */
        $currentAdmin = auth('admin')->user();

        $admin = Admin::query()
            ->where('id', $id)
            ->where('username', '!=', $currentAdmin->username)
            ->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->enrichAdmins(collect([$admin]));

        return response()->json([
            'status' => true,
            'data' => new AdminAccountResource($admin),
        ]);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        $data = $this->adminPayloadFromRequest($request->validated());
        $data['password'] = md5((string) $request->input('password'));
        $data['addtime'] = time();
        $data['last_login_time'] = $data['last_login_time'] ?? 0;
        $data['last_login_ip'] = $data['last_login_ip'] ?? 0;
        $data['endtime'] = $data['endtime'] ?? 0;
        $data['status'] = $data['status'] ?? 1;
        $data['level'] = $data['level'] ?? 1;
        $data['sort'] = $data['sort'] ?? 0;

        $admin = Admin::query()->create($data);

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new AdminAccountResource($admin),
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateAdminRequest $request, int $id): JsonResponse
    {
        /** @var Admin $currentAdmin */
        $currentAdmin = auth('admin')->user();

        $admin = Admin::query()
            ->where('id', $id)
            ->where('username', '!=', $currentAdmin->username)
            ->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $this->adminPayloadFromRequest($request->validated());

        if ($request->filled('password')) {
            $data['password'] = md5((string) $request->input('password'));
        }

        if (!$admin->update($data)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->enrichAdmins(collect([$admin]));

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new AdminAccountResource($admin),
        ]);
    }

    public function updateStatus(UpdateAdminStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $type = strtolower((string) $request->input('type'));
        $query = Admin::query()->whereIn('id', $ids);

        if ($type === 'del') {
            if (!$query->delete()) {
                return response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            AuthGroupAccess::query()->whereIn('uid', $ids)->delete();

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

        if ($data === null || !$query->update($data)) {
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
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function adminPayloadFromRequest(array $validated): array
    {
        if (isset($validated['mobile']) && !isset($validated['moble'])) {
            $validated['moble'] = $validated['mobile'];
        }

        unset($validated['mobile']);

        return $validated;
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
     * @param Collection<int, Admin> $admins
     */
    private function enrichAdmins(Collection $admins): void
    {
        if ($admins->isEmpty()) {
            return;
        }

        $adminIds = $admins->pluck('id')->all();

        $accessRows = AuthGroupAccess::query()
            ->whereIn('uid', $adminIds)
            ->get()
            ->keyBy('uid');

        $groupIds = $accessRows->pluck('group_id')->unique()->filter()->all();

        $groups = AuthGroup::query()
            ->whereIn('id', $groupIds)
            ->get()
            ->keyBy('id');

        foreach ($admins as $admin) {
            $access = $accessRows->get($admin->id);

            if (!$access) {
                $admin->auth_group = null;
                continue;
            }

            $group = $groups->get($access->group_id);

            $admin->auth_group = $group ? [
                'id' => $group->id,
                'title' => $group->title,
                'description' => $group->description,
                'status' => (int) $group->status,
            ] : null;
        }
    }
}
