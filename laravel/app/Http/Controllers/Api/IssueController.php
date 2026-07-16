<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Issue;
use App\Models\IssueLog;
use App\Models\UserCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class IssueController extends Controller
{
    public function normalissue(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get normalissue
            $normalissue = IssueLog::where('uid', $user->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Normal issue retrieved successfully',
                'data' => $normalissue->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Normal issue retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve normal issue. Try again later.',
            ], 500);
        }
    }
    public function overdueissue(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get overdueissue
            $overdueissue = IssueLog::where('uid', $user->id)
                ->where('status', 2)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Overdue issue retrieved successfully',
                'data' => $overdueissue->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Overdue issue retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve overdueissue. Try again later.',
            ], 500);
        }
    }
    public function me(Request $request)
    {
        try {
            $user = JWTAuth::user();
            // Get staking
            $staking = IssueLog::where('uid', $user->id)
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Staking retrieved successfully',
                'data' => $staking->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Overdue issue retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve staking. Try again later.',
            ], 500);
        }
    }
    public function getList(Request $request)
    {
        try {
            // Get staking
            $staking = Issue::where('status', 1)
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Staking retrieved successfully',
                'data' => $staking->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Overdue issue retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve staking. Try again later.',
            ], 500);
        }
    }
    public function details(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:tw_issue,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'info' => $validator->errors()->first(),
                ], 422);
            }
            // Get staking
            $staking = Issue::find($request->id);

            return response()->json([
                'status' => true,
                'message' => 'Staking retrieved successfully',
                'data' => $staking->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Overdue issue retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve staking. Try again later.',
            ], 500);
        }
    }

    public function subscribeIssue(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'pid' => 'required|integer',
                'amount' => 'required|numeric|gt:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'info' => $validator->errors()->first(),
                ], 422);
            }

            // Get authenticated user
            $user = JWTAuth::user();

            // Check rzstatus
            if ($user->rzstatus != 2) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Please complete identity verification first.',
                ], 403);
            }

            // Find staking
            $staking = Issue::find($request->pid);
            if (!$staking) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Cannot find the staking',
                ], 422);
            }

            if ($staking->state != 1) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Staking session is not open for registration',
                ], 422);
            }

            // Validate num
            $num = $request->amount;
            if ($num < $staking->min) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Cannot be less than the minimum purchase quantity',
                ], 422);
            }

            if ($num > $staking->max) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Cannot be greater than the maximum purchase quantity',
                ], 422);
            }

            // Check balance
            $buyCoin = strtolower("USDT"); // Default USDT coin
            $userCoin = UserCoin::where('userid', $user->id)->first();
            if (!$userCoin || $userCoin->$buyCoin < $num) {
                return response()->json([
                    'code' => 0,
                    'info' => 'Insufficient balance in ' . $buyCoin,
                ], 422);
            }

            // Start transaction
            DB::beginTransaction();

            // Create issue log
            IssueLog::create([
                'pid' => $request->pid,
                'uid' => $user->id,
                'account' => $user->username,
                'name' => $staking->name,
                'num' => $num,
                'open' => $staking->open,
                'percent' => $staking->percent,
                'addtime' => now()->format('Y-m-d H:i:s'),
                'endtime' => now()->addDays($staking->open)->format('Y-m-d H:i:s'),
                'endday' => now()->addDays($staking->open)->format('Y-m-d'),
                'status' => 1,
            ]);

            // Deduct user balance
            $userCoin->decrement($buyCoin, $num);
            $userCoin->increment($buyCoin . '_d', $num);

            // Create bill for deduction
            Bill::create([
                'uid' => $user->id,
                'username' => $user->username,
                'num' => $num,
                'coinname' => $buyCoin,
                'afternum' => $userCoin->$buyCoin,
                'type' => 11,
                'addtime' => now()->format('Y-m-d H:i:s'),
                'st' => 2,
                'remark' => $staking->name . ' subscription',
            ]);

            DB::commit();

            return response()->json([
                'code' => 1,
                'info' => 'Subscribe issue successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to subscribe issue', [
                'user_id' => auth()->id(),
                'pid' => $request->pid,
                'num' => $request->amount,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'code' => 0,
                'info' => 'Failed to subscribe issue. Please try again later.',
            ], 500);
        }
    }
}
