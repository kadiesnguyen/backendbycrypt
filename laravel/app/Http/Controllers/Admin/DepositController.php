<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListDepositsRequest;
use App\Http\Resources\Admin\DepositResource;
use App\Models\Bill;
use App\Models\Notice;
use App\Models\Recharge;
use App\Models\UserCoin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class DepositController extends Controller
{
    public function index(ListDepositsRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));

        $query = Recharge::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => DepositResource::collection(collect($paginator->items())),
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

        try {
            DB::transaction(function () use ($id): void {
                $recharge = Recharge::query()->lockForUpdate()->find($id);

                if (!$recharge) {
                    throw new \RuntimeException('not_found');
                }

                if ((int) $recharge->status !== 1) {
                    throw new \RuntimeException('processed');
                }

                $uid = (int) $recharge->uid;
                $num = $recharge->num;
                $coinname = strtolower(trim((string) $recharge->coin));

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

                $updated = $recharge->update([
                    'updatetime' => $now,
                    'status' => 2,
                ]);

                $incremented = UserCoin::query()
                    ->where('userid', $uid)
                    ->increment($coinname, $num);

                $bill = Bill::query()->create([
                    'uid' => $recharge->uid,
                    'username' => $recharge->username,
                    'num' => $num,
                    'coinname' => $coinname,
                    'afternum' => $beforeBalance + (float) $num,
                    'type' => 17,
                    'addtime' => $now,
                    'st' => 1,
                    'remark' => 'Nạp tiền vào tài khoản',
                ]);

                if (!$updated || !$incremented || !$bill) {
                    throw new \RuntimeException('system_error');
                }

                Notice::query()->create([
                    'uid' => $recharge->uid,
                    'account' => $recharge->username,
                    'title' => 'Xem xét tiền gửi',
                    'content' => 'Số tiền nạp của bạn đã được nhận, hãy chú ý kiểm tra',
                    'addtime' => $now,
                    'status' => 1,
                ]);
            });
        } catch (\RuntimeException $e) {
            return match ($e->getMessage()) {
                'not_found' => response()->json([
                    'status' => false,
                    'message' => 'Deposit order does not exist.',
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

    public function reject(int $id): JsonResponse
    {
        if ($id <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $recharge = Recharge::query()->find($id);

        if (!$recharge) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit order does not exist.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $recharge->status !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'The order has been processed.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $now = now()->format('Y-m-d H:i:s');
        $updated = $recharge->update([
            'updatetime' => $now,
            'status' => 3,
        ]);

        if (!$updated) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot refuse.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Notice::query()->create([
            'uid' => $recharge->uid,
            'account' => $recharge->username,
            'title' => 'Xem xét tiền gửi',
            'content' => 'Yêu cầu nạp tiền của bạn đã bị hệ thống từ chối, vui lòng liên hệ với chăm sóc khách hàng',
            'addtime' => $now,
            'status' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Deposit was declined.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $recharge = Recharge::query()->find($id);

        if (!$recharge) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit order does not exist.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$recharge->delete()) {
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
