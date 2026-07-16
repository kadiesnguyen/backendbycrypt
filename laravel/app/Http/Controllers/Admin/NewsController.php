<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteNewsRequest;
use App\Http\Requests\Admin\ListNewsRequest;
use App\Http\Requests\Admin\UpsertNewsRequest;
use App\Http\Resources\Admin\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * List news items (ThinkPHP News/index).
     */
    public function index(ListNewsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $paginator = News::query()
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => NewsResource::collection(collect($paginator->items())),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    /**
     * Show a news item for editing (ThinkPHP News/edit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $news = News::query()->find($id);

        if (!$news) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new NewsResource($news),
        ]);
    }

    /**
     * Create a news item (ThinkPHP News/ggeditup POST without id).
     */
    public function store(UpsertNewsRequest $request): JsonResponse
    {
        $news = News::query()->create($this->newsPayload($request));

        if (!$news) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot add news.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'News added successfully.',
            'data' => new NewsResource($news),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a news item (ThinkPHP News/ggeditup POST with id).
     */
    public function update(UpsertNewsRequest $request, int $id): JsonResponse
    {
        $news = News::query()->find($id);

        if (!$news) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$news->update($this->newsPayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'Edit news failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Edit news successfully.',
            'data' => new NewsResource($news->fresh()),
        ]);
    }

    /**
     * Bulk delete news items (ThinkPHP News/setstatus).
     */
    public function destroy(DeleteNewsRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $items = News::query()
            ->whereIn('id', $ids)
            ->get(['id', 'title']);

        if ($items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No data selected.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deleted = News::query()->whereIn('id', $ids)->delete();

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
     * KindEditor image upload (ThinkPHP News/uploadImage).
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
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png,webp', 'max:5120'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => 1,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = public_path('Upload/news');

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
            'url' => '/Upload/news/' . $filename,
        ]);
    }

    /**
     * Cover image upload (ThinkPHP News/wenzhangimg).
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
            'file' => ['required', 'file', 'mimes:jpg,jpeg,gif,png,webp', 'max:5120'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validated->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = public_path('Upload/news');

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
    private function newsPayload(UpsertNewsRequest $request): array
    {
        $content = (string) $request->input('content', '');
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content) ?? '';

        return [
            'title' => trim((string) $request->input('title')),
            'coverImage' => $this->normalizeCoverImage($request->input('coverImage')),
            'content' => $content,
            'status' => (int) $request->input('status', 1),
        ];
    }

    private function normalizeCoverImage(mixed $image): string
    {
        $img = trim((string) $image);

        if ($img === '') {
            return '';
        }

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }

        $host = request()->getSchemeAndHttpHost();

        return $host . '/Upload/news/' . ltrim($img, '/');
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
