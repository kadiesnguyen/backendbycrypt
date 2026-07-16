<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListWithdrawalsRequest;
use App\Http\Resources\Admin\WithdrawalResource;
use App\Models\Bill;
use App\Models\Myzc;
use App\Models\Notice;
use App\Models\UserCoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class WithdrawalController extends Controller
{
    public function index(ListWithdrawalsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));

        $query = Myzc::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => WithdrawalResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function approve(int $id): JsonResponse
    {
        if ($id <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $withdrawal = Myzc::query()->find($id);

        if (!$withdrawal) {
            return response()->json([
                'status' => false,
                'message' => 'Withdrawal order does not exist.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $withdrawal->status !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'The order has been processed.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $now = now()->format('Y-m-d H:i:s');
        $updated = $withdrawal->update([
            'endtime' => $now,
            'status' => 2,
        ]);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Notice::query()->create([
            'uid' => $withdrawal->userid,
            'account' => $withdrawal->username,
            'title' => 'Xem xét rút tiền',
            'content' => 'Yêu cầu rút tiền của bạn đã được phê duyệt, vui lòng kiểm tra',
            'addtime' => $now,
            'status' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
        ]);
    }

    public function reject(int $id): JsonResponse
    {
        if ($id <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::transaction(function () use ($id): void {
                $withdrawal = Myzc::query()->lockForUpdate()->find($id);

                if (!$withdrawal) {
                    throw new \RuntimeException('not_found');
                }

                if ((int) $withdrawal->status !== 1) {
                    throw new \RuntimeException('processed');
                }

                $uid = (int) $withdrawal->userid;
                $num = $withdrawal->num;
                $coinname = strtolower(trim((string) $withdrawal->coinname));

                if (!Schema::hasColumn('tw_user_coin', $coinname)) {
                    throw new \RuntimeException('invalid_coin');
                }

                $userCoin = UserCoin::query()
                    ->where('userid', $uid)
                    ->lockForUpdate()
                    ->first();

                if (!$userCoin) {
                    throw new \RuntimeException('no_wallet');
                }

                $beforeBalance = (float) ($userCoin->{$coinname} ?? 0);
                $now = now()->format('Y-m-d H:i:s');

                $updated = $withdrawal->update([
                    'endtime' => $now,
                    'status' => 3,
                ]);

                $incremented = UserCoin::query()
                    ->where('userid', $uid)
                    ->increment($coinname, $num);

                $bill = Bill::query()->create([
                    'uid' => $uid,
                    'username' => $withdrawal->username,
                    'num' => $num,
                    'coinname' => $withdrawal->coinname,
                    'afternum' => $beforeBalance + (float) $num,
                    'type' => 16,
                    'addtime' => $now,
                    'st' => 1,
                    'remark' => 'Rút tiền bị từ chối, tiền được trả lại',
                ]);

                if (!$updated || !$incremented || !$bill) {
                    throw new \RuntimeException('system_error');
                }

                Notice::query()->create([
                    'uid' => $uid,
                    'account' => $withdrawal->username,
                    'title' => 'Xem xét rút tiền',
                    'content' => 'Yêu cầu rút tiền của bạn đã bị từ chối, vui lòng liên hệ với quản trị viên',
                    'addtime' => $now,
                    'status' => 1,
                ]);
            });
        } catch (\RuntimeException $e) {
            return match ($e->getMessage()) {
                'not_found' => response()->json([
                    'status' => false,
                    'message' => 'Withdrawal order does not exist.',
                ], Response::HTTP_NOT_FOUND),
                'processed' => response()->json([
                    'status' => false,
                    'message' => 'The order has been processed.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY),
                'invalid_coin', 'no_wallet' => response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        } catch (\Throwable) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $withdrawal = Myzc::query()->find($id);

        if (!$withdrawal) {
            return response()->json([
                'status' => false,
                'message' => 'Withdrawal order does not exist.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$withdrawal->delete()) {
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
}
