<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListOnlineSupportRequest;
use App\Http\Requests\Admin\ReplyOnlineSupportRequest;
use App\Http\Resources\Admin\OnlineMessageResource;
use App\Http\Resources\Admin\OnlineSupportUserResource;
use App\Models\Online;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class OnlineSupportController extends Controller
{
    public function index(ListOnlineSupportRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 50);

        $query = User::query()
            ->select(['id', 'username'])
            ->orderByDesc('id');

        if ($request->filled('field') && $request->filled('name')) {
            $query->where($request->input('field'), $request->input('name'));
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status'));
        }

        $paginator = $query->paginate($perPage);
        $users = collect($paginator->items());
        $this->attachPendingCounts($users);

        $filtered = $users
            ->filter(fn (User $user) => (int) ($user->pending_count ?? 0) > 0)
            ->sortByDesc('pending_count')
            ->values();

        return response()->json([
            'status' => true,
            'data' => OnlineSupportUserResource::collection($filtered),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'filtered_total' => $filtered->count(),
            ],
        ]);
    }

    public function userMessages(int $userId): JsonResponse
    {
        $perPage = (int) request()->input('per_page', 50);

        $paginator = Online::query()
            ->where('uid', $userId)
            ->where('type', 2)
            ->where('state', 0)
            ->orderByDesc('state')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => OnlineMessageResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function showMessage(int $id): JsonResponse
    {
        $message = Online::query()->find($id);

        if (!$message) {
            return response()->json([
                'status' => false,
                'message' => 'Message not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new OnlineMessageResource($message),
        ]);
    }

    public function reply(ReplyOnlineSupportRequest $request, int $id): JsonResponse
    {
        if ($id <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $message = Online::query()->find($id);

        if (!$message) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        $reply = Online::query()->create([
            'uid' => $message->uid,
            'username' => $message->username,
            'content' => $request->input('content'),
            'type' => 1,
            'addtime' => now()->format('Y-m-d H:i:s'),
            'state' => 1,
        ]);

        if (!$reply) {
            return response()->json([
                'status' => false,
                'message' => 'Reply failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message->update(['state' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Reply successful.',
            'data' => new OnlineMessageResource($reply),
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Collection<int, User> $users
     */
    private function attachPendingCounts(Collection $users): void
    {
        if ($users->isEmpty()) {
            return;
        }

        $userIds = $users->pluck('id')->all();

        $counts = Online::query()
            ->whereIn('uid', $userIds)
            ->where('state', 0)
            ->selectRaw('uid, count(*) as pending_count')
            ->groupBy('uid')
            ->pluck('pending_count', 'uid');

        foreach ($users as $user) {
            $user->pending_count = (int) ($counts[$user->id] ?? 0);
        }
    }
}
