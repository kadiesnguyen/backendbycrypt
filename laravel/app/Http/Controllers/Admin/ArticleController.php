<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteArticlesRequest;
use App\Http\Requests\Admin\ListArticlesRequest;
use App\Http\Requests\Admin\UpsertArticleRequest;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * List articles/notifications (ThinkPHP Article/index).
     */
    public function index(ListArticlesRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $query = Content::query()->orderByDesc('id');

        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('status', (int) $status);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => ArticleResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Show an article for editing (ThinkPHP Article/edit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $article = Content::query()->find($id);

        if (!$article) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new ArticleResource($article),
        ]);
    }

    /**
     * Create an article (ThinkPHP Article/ggeditup without id).
     */
    public function store(UpsertArticleRequest $request): JsonResponse
    {
        $payload = $this->articlePayload($request);
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        $article = Content::query()->create($payload);

        if (!$article) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot add notification.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification added successfully.',
            'data' => new ArticleResource($article),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update an article (ThinkPHP Article/ggeditup with id).
     */
    public function update(UpsertArticleRequest $request, int $id): JsonResponse
    {
        $article = Content::query()->find($id);

        if (!$article) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$article->update($this->articlePayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'Edit notification failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit notification successfully.',
            'data' => new ArticleResource($article->fresh()),
        ]);
    }

    /**
     * Bulk delete articles (ThinkPHP Article/setstatus).
     */
    public function destroy(DeleteArticlesRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $items = Content::query()
            ->whereIn('id', $ids)
            ->get(['id', 'title']);

        if ($items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No data selected.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deleted = Content::query()->whereIn('id', $ids)->delete();

        if (!$deleted) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Delete successfully.',
        ]);
    }

    /**
     * KindEditor image upload (ThinkPHP Article/uploadImage).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $file = $request->file('imgFile') ?? $request->file('file');

        if (!$file) {
            return response()->json([
                'error' => 1,
                'message' => 'No file uploaded.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = validator(['file' => $file], [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png', 'max:5120'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => 1,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = public_path('Upload/article');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            return response()->json([
                'error' => 1,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return response()->json([
            'error' => 0,
            'url' => '/Upload/article/' . $filename,
        ]);
    }

    /**
     * Thumbnail upload (ThinkPHP Article/wenzhangimg).
     */
    public function uploadCover(Request $request): JsonResponse
    {
        $file = $request->file('upload_file0') ?? $request->file('file');

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

        $directory = public_path('Upload/article');

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
    private function articlePayload(UpsertArticleRequest $request): array
    {
        return [
            'title' => trim((string) $request->input('title')),
            'img' => trim((string) $request->input('img', '')),
            'content' => (string) $request->input('content', ''),
            'status' => (int) $request->input('status', 1),
        ];
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
