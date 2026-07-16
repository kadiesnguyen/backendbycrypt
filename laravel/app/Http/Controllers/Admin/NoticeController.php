<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteNoticesRequest;
use App\Http\Requests\Admin\ListNoticesRequest;
use App\Http\Requests\Admin\SendNoticeRequest;
use App\Http\Resources\Admin\NoticeResource;
use App\Models\Notice;
use App\Models\User;
use App\Support\NotificationTtl;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NoticeController extends Controller
{
    public function index(ListNoticesRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $account = $request->input('account', $request->input('username'));

        NotificationTtl::purgeExpiredNotices();

        $query = NotificationTtl::scopeActiveNotices(Notice::query())->orderByDesc('id');

        if ($account !== null && $account !== '') {
            $query->where('account', $account);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => NoticeResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function sendForm(int $userId, int $type): JsonResponse
    {
        if (!in_array($type, [1, 2], true)) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied.',
            ], Response::HTTP_FORBIDDEN);
        }

        $payload = [
            'type' => $type,
            'user_id' => $userId,
        ];

        if ($type === 1) {
            if ($userId <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing params.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::query()->select(['id', 'username'])->find($userId);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing params.',
                ], Response::HTTP_NOT_FOUND);
            }

            $payload['user'] = [
                'id' => $user->id,
                'username' => $user->username,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $payload,
        ]);
    }

    public function store(SendNoticeRequest $request): JsonResponse
    {
        $type = (int) $request->input('type');
        $now = now()->format('Y-m-d H:i:s');
        $noticeData = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'imgs' => $request->input('imgs'),
            'addtime' => $now,
            'status' => 1,
            'user_view' => 1,
        ];

        if ($type === 1) {
            $userId = (int) $request->input('user_id');

            if ($userId <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing params.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::query()->select(['id', 'username'])->find($userId);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing params.',
                ], Response::HTTP_NOT_FOUND);
            }

            $created = Notice::query()->create([
                ...$noticeData,
                'uid' => $user->id,
                'account' => $user->username,
            ]);

            if (!$created) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notice send failed.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Notice sent successfully.',
                'data' => new NoticeResource($created),
            ], Response::HTTP_CREATED);
        }

        $users = User::query()->select(['id', 'username'])->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No users found.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        foreach ($users as $user) {
            Notice::query()->create([
                ...$noticeData,
                'uid' => $user->id,
                'account' => $user->username,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Bulk notice sent successfully.',
        ], Response::HTTP_CREATED);
    }

    public function destroy(DeleteNoticesRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Notice::query()->whereIn('id', $ids)->delete()) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Delete successfully.',
        ]);
    }

    /**
     * @param mixed $ids
     * @return array<int, int>
     */
    private function normalizeIds(mixed $ids): array
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        if (!is_array($ids)) {
            return [];
        }

        return array_values(array_filter(array_map('intval', $ids), fn (int $id) => $id > 0));
    }
}
