<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListContractOrdersRequest;
use App\Http\Resources\Admin\ContractOrderResource;
use App\Models\Tyhyorder;
use Illuminate\Http\JsonResponse;

class TrialOrderController extends Controller
{
    /**
     * Trial contract orders (ThinkPHP Trade/tyorder).
     */
    public function index(ListContractOrdersRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username');

        $query = Tyhyorder::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        if ($request->filled('hyzd') && (int) $request->input('hyzd') > 0) {
            $query->where('hyzd', (int) $request->input('hyzd'));
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => ContractOrderResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
