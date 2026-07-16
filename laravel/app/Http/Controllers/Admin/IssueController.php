<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListIssueLogsRequest;
use App\Http\Requests\Admin\ListIssuesRequest;
use App\Http\Requests\Admin\UpsertIssueRequest;
use App\Http\Resources\Admin\IssueLogResource;
use App\Http\Resources\Admin\IssueResource;
use App\Models\Coin;
use App\Models\Issue;
use App\Models\IssueLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IssueController extends Controller
{
    /**
     * List staking packages (ThinkPHP Issue/index).
     */
    public function index(ListIssuesRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $paginator = Issue::query()
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => IssueResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Form metadata for create (ThinkPHP Issue/edit GET without id).
     */
    public function formMeta(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Show a staking package for editing (ThinkPHP Issue/edit GET with id).
     */
    public function show(int $id): JsonResponse
    {
        $issue = Issue::query()->find($id);

        if (!$issue) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new IssueResource($issue),
            'meta' => $this->buildFormMeta(),
        ]);
    }

    /**
     * Create a staking package (ThinkPHP Issue/save POST without id).
     */
    public function store(UpsertIssueRequest $request): JsonResponse
    {
        $payload = $this->issuePayload($request);
        $payload['addtime'] = now()->format('Y-m-d H:i:s');

        $issue = Issue::query()->create($payload);

        if (!$issue) {
            return response()->json([
                'status' => false,
                'message' => 'Add unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Add successfully.',
            'data' => new IssueResource($issue),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update a staking package (ThinkPHP Issue/save POST with id).
     */
    public function update(UpsertIssueRequest $request, int $id): JsonResponse
    {
        $issue = Issue::query()->find($id);

        if (!$issue) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$issue->update($this->issuePayload($request))) {
            return response()->json([
                'status' => false,
                'message' => 'Update unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Update successfully.',
            'data' => new IssueResource($issue->fresh()),
        ]);
    }

    /**
     * Upload package image (ThinkPHP Issue/issueimage).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'image', 'max:3072'],
        ]);

        $file = $validated['file'];
        $directory = public_path('Upload/public');

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
     * List staking subscription logs (ThinkPHP Issue/log).
     */
    public function logs(ListIssueLogsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $account = $request->input('account', $request->input('name'));

        $query = IssueLog::query()->orderByDesc('id');

        if ($account !== null && $account !== '') {
            $query->where('account', trim((string) $account));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => IssueLogResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildFormMeta(): array
    {
        $mapCoin = fn (Coin $coin) => [
            'name' => $coin->name,
            'title' => $coin->title,
        ];

        $clist = Coin::query()
            ->where(function ($query) {
                $query->where('type', 3)->orWhere('type', 2);
            })
            ->orderByDesc('id')
            ->get(['name', 'title'])
            ->map($mapCoin)
            ->values()
            ->all();

        $paylist = Coin::query()
            ->where(function ($query) {
                $query->where('type', 1)->orWhere('type', 2);
            })
            ->orderByDesc('id')
            ->get(['name', 'title'])
            ->map($mapCoin)
            ->values()
            ->all();

        $alllist = Coin::query()
            ->orderByDesc('id')
            ->get(['name', 'title'])
            ->map($mapCoin)
            ->values()
            ->all();

        return [
            'clist' => $clist,
            'paylist' => $paylist,
            'alllist' => $alllist,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function issuePayload(UpsertIssueRequest $request): array
    {
        return [
            'name' => $request->input('name'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
            'open' => (int) $request->input('open'),
            'percent' => $request->input('percent'),
            'imgs' => $request->input('imgs') ?? '',
            'content' => $request->input('content') ?? '',
            'status' => (int) $request->input('status'),
            'state' => (int) $request->input('state'),
        ];
    }
}
