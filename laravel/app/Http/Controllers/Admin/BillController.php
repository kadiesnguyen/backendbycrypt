<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteBillsRequest;
use App\Http\Resources\Admin\BillResource;
use App\Models\Bill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BillController extends Controller
{
    /**
     * Fund change logs (ThinkPHP User/amountlog, Finance/index).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));
        $coinname = $request->input('coinname');
        $st = $request->input('st');

        $query = Bill::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        if ($coinname !== null && $coinname !== '') {
            $query->where('coinname', $coinname);
        }

        if ($st !== null && $st !== '' && (int) $st > 0) {
            $query->where('st', (int) $st);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => BillResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function destroy(DeleteBillsRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Bill::query()->whereIn('id', $ids)->delete()) {
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
