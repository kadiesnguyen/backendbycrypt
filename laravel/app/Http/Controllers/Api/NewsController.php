<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        $limit = (int) $request->query('limit', 20);

        $news = News::where('status', 1)
            ->orderByDesc('id')
            ->paginate($limit);

        return response()->json([
            'status' => true,
            'message' => 'News retrieved successfully',
            'data' => [
                'items' => $news->items(),
                'pagination' => [
                    'current_page' => $news->currentPage(),
                    'last_page' => $news->lastPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                ],
            ],
        ], 200);
    }

    public function detail($id)
    {
        $news = News::where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$news) {
            return response()->json([
                'status' => false,
                'message' => 'News not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'News retrieved successfully',
            'data' => $news,
        ], 200);
    }
}
