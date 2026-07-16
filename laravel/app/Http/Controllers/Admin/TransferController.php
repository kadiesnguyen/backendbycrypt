<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListTransfersRequest;
use App\Http\Resources\Admin\TransferResource;
use App\Models\CoinExchangeHistory;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    public function index(ListTransfersRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));

        $query = CoinExchangeHistory::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => TransferResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
