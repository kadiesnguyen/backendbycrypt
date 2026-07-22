<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PerpTradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PerpController extends Controller
{
    public function __construct(private PerpTradingService $perp)
    {
    }

    public function settings()
    {
        return response()->json([
            'status' => true,
            'message' => 'Perpetual settings retrieved.',
            'data' => $this->perp->settings(),
        ]);
    }

    public function balance()
    {
        $user = JWTAuth::user();

        return response()->json([
            'status' => true,
            'message' => 'Perpetual balance retrieved.',
            'data' => $this->perp->balance($user),
        ]);
    }

    public function positions()
    {
        $user = JWTAuth::user();

        return response()->json([
            'status' => true,
            'message' => 'Open positions retrieved.',
            'data' => $this->perp->positions($user),
        ]);
    }

    public function history(Request $request)
    {
        $user = JWTAuth::user();
        $limit = max(1, min(100, (int) $request->input('limit', 50)));

        return response()->json([
            'status' => true,
            'message' => 'Position history retrieved.',
            'data' => $this->perp->history($user, $limit),
        ]);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string|max:30',
            'side' => 'required|string|in:buy,sell',
            'qty' => 'required|numeric|gt:0',
            'leverage' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = JWTAuth::user();
        $result = $this->perp->placeOrder(
            $user,
            (string) $request->symbol,
            (string) $request->side,
            (float) $request->qty,
            (int) $request->leverage
        );

        $code = ($result['status'] ?? false) ? 200 : 422;

        return response()->json($result, $code);
    }

    public function close(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_id' => 'nullable|integer|gt:0',
            'symbol' => 'nullable|string|max:30',
            'qty' => 'nullable|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if (!$request->filled('position_id') && !$request->filled('symbol')) {
            return response()->json([
                'status' => false,
                'message' => 'position_id or symbol required.',
            ], 422);
        }

        $user = JWTAuth::user();
        $result = $this->perp->closePosition(
            $user,
            $request->input('position_id') ? (int) $request->input('position_id') : null,
            $request->input('symbol'),
            $request->filled('qty') ? (float) $request->qty : null
        );

        $code = ($result['status'] ?? false) ? 200 : 422;

        return response()->json($result, $code);
    }
}
