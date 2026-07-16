<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddUserFundsRequest;
use App\Http\Requests\Admin\BulkUpdateUserStatusRequest;
use App\Http\Requests\Admin\KycReviewRequest;
use App\Http\Requests\Admin\ListUsersRequest;
use App\Http\Requests\Admin\UpdateUserTradeLockRequest;
use App\Http\Requests\Admin\UpdateUserAssetRequest;
use App\Http\Requests\Admin\UpdateUserLoginLogStatusRequest;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Http\Requests\Admin\UpsertUserRequest;
use App\Http\Requests\Admin\UpsertUserWalletRequest;
use App\Http\Resources\Admin\AgentResource;
use App\Http\Resources\Admin\UserCoinResource;
use App\Http\Resources\Admin\UserLogResource;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Admin\UserWalletResource;
use App\Models\Admin;
use App\Models\Bill;
use App\Models\Kjorder;
use App\Models\Kuangji;
use App\Models\Notice;
use App\Models\User;
use App\Models\UserCoin;
use App\Models\UserLog;
use App\Models\UserQianbao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(ListUsersRequest $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $perPage = (int) $request->input('per_page', 15);

        $query = User::query()->orderByDesc('id');

        if ($request->filled('username')) {
            $query->where('username', $request->input('username'));
        } elseif ($admin->username) {
            $query->where('username', '!=', $admin->username);
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status'));
        }

        $paginator = $query->paginate($perPage);
        $users = collect($paginator->items());
        $this->enrichUsers($users);

        return response()->json([
            'status' => true,
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->enrichUsers(collect([$user]));

        return response()->json([
            'status' => true,
            'data' => new UserResource($user),
        ]);
    }

    public function store(UpsertUserRequest $request): JsonResponse
    {
        $username = trim((string) $request->input('username'));

        if (User::query()->where('username', $username)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Username already exists.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $invitCode = trim((string) $request->input('invit', ''));
        $invitUser = null;

        if ($invitCode !== '' && $invitCode !== '0') {
            $invitUser = User::query()->where('invit', $invitCode)->first();

            if (!$invitUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Referrer not exist.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $payload = [
            'username' => $username,
            'password' => (string) $request->input('password'),
            'paypassword' => $request->filled('paypassword') ? (string) $request->input('paypassword') : null,
            'fullname' => $request->input('fullname'),
            'phonenumber' => $request->input('phonenumber'),
            'bank_name' => $request->input('bank_name'),
            'bank_acc_no' => $request->input('bank_acc_no'),
            'bank_acc_name' => $request->input('bank_acc_name'),
            'wallet' => $request->input('wallet'),
            'status' => (int) $request->input('status', 2),
            'txstate' => $request->filled('paypassword') ? 1 : (int) $request->input('txstate', 1),
            'wdstatus' => $request->filled('paypassword') ? 1 : 0,
            'invit_1' => $invitUser?->id ?? 0,
            'invit_2' => $invitUser?->invit_1 ?? 0,
            'invit_3' => $invitUser?->invit_2 ?? 0,
            'path' => $invitUser ? trim(($invitUser->path ?? '') . ',' . $invitUser->id, ',') : '',
            'addtime' => time(),
            'addip' => $request->ip(),
            'invit' => $this->generateInvitCode(),
        ];

        $user = User::query()->create($payload);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot add.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        UserCoin::query()->create(['userid' => $user->id]);

        return response()->json([
            'status' => true,
            'message' => 'Add successfully.',
            'data' => new UserResource($user),
        ], Response::HTTP_CREATED);
    }

    public function update(UpsertUserRequest $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->filled('cccd')) {
            $exists = User::query()
                ->where('cccd', $request->input('cccd'))
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'CCCD existed.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $payload = $request->only([
            'username', 'fullname', 'phonenumber', 'cccd', 'status', 'txstate',
            'bank_name', 'bank_acc_no', 'bank_acc_name', 'wallet',
            'invit_1', 'invit_2', 'invit_3', 'hy_result_mode', 'kefu',
        ]);

        if ($request->filled('password')) {
            $payload['password'] = (string) $request->input('password');
        }

        if ($request->filled('paypassword')) {
            $payload['paypassword'] = (string) $request->input('paypassword');
            $payload['txstate'] = 1;
            $payload['wdstatus'] = 1;
        }

        if (isset($payload['status']) && (int) $payload['status'] === 1 && $user->username) {
            Admin::query()->where('username', $user->username)->update(['status' => 2]);
        }

        if (!$user->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->enrichUsers(collect([$user->fresh()]));

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function updateStatus(UpdateUserStatusRequest $request, int $id): JsonResponse
    {
        return $this->applyUserStatusMutation([(int) $id], (int) $request->input('type'));
    }

    public function updateTradeLock(UpdateUserTradeLockRequest $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $locked = $request->boolean('locked');
        $message = trim((string) $request->input('message', ''));

        $payload = [
            'trade_locked' => $locked ? 1 : 0,
            'trade_lock_msg' => $locked
                ? ($message !== '' ? $message : User::DEFAULT_TRADE_LOCK_MESSAGE)
                : null,
        ];

        if (!$user->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->enrichUsers(collect([$user->fresh()]));

        return response()->json([
            'status' => true,
            'message' => $locked ? 'Trade locked.' : 'Trade unlocked.',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function bulkUpdateStatus(BulkUpdateUserStatusRequest $request): JsonResponse
    {
        return $this->applyUserStatusMutation($this->normalizeIds($request->input('ids')), (int) $request->input('type'));
    }

    public function addFunds(AddUserFundsRequest $request, int $id): JsonResponse
    {
        $user = User::query()->select(['id', 'username'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_NOT_FOUND);
        }

        $amount = (float) $request->input('amount');
        $userCoin = UserCoin::query()->where('userid', $user->id)->first();

        if (!$userCoin) {
            $userCoin = UserCoin::query()->create(['userid' => $user->id]);
        }

        if (!$userCoin) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $before = (float) ($userCoin->usdt ?? 0);
        $incremented = UserCoin::query()->where('userid', $user->id)->increment('usdt', $amount);

        $bill = Bill::query()->create([
            'uid' => $user->id,
            'username' => $user->username,
            'num' => $amount,
            'coinname' => 'usdt',
            'afternum' => $before + $amount,
            'type' => 17,
            'addtime' => now()->format('Y-m-d H:i:s'),
            'st' => 1,
            'remark' => 'Admin cộng USDT thủ công',
        ]);

        if (!$incremented || !$bill) {
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

    public function kycReview(KycReviewRequest $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_NOT_FOUND);
        }

        $rzstatus = (int) $request->input('rzstatus');
        $username = (string) $request->input('username');

        if (!$user->update(['rzstatus' => $rzstatus, 'rzuptime' => time()])) {
            return response()->json([
                'status' => false,
                'message' => 'System error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($rzstatus === 2) {
            $kjid = (int) $request->input('kjid');
            $miner = Kuangji::query()->find($kjid);

            if ($miner) {
                Kjorder::query()->create([
                    'kid' => $miner->id,
                    'type' => 1,
                    'sharebl' => '',
                    'uid' => $user->id,
                    'username' => $username,
                    'kjtitle' => $miner->title,
                    'imgs' => $miner->imgs,
                    'status' => 1,
                    'cycle' => $miner->cycle,
                    'synum' => $miner->cycle,
                    'outtype' => $miner->outtype,
                    'outcoin' => $miner->outcoin,
                    'outnum' => (int) $miner->outtype === 2 ? $miner->dayoutnum : '',
                    'outusdt' => (int) $miner->outtype === 1 ? $miner->dayoutnum : '',
                    'djout' => $miner->djout,
                    'djnum' => $miner->djday,
                    'addtime' => now()->format('Y-m-d H:i:s'),
                    'endtime' => now()->addDays((int) $miner->cycle)->format('Y-m-d H:i:s'),
                    'intaddtime' => time(),
                    'intendtime' => time() + 86400 * (int) $miner->cycle,
                ]);
            }

            Notice::query()->create([
                'uid' => $user->id,
                'account' => $username,
                'title' => 'Kiểm tra chứng nhận thành công',
                'content' => 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công',
                'addtime' => now()->format('Y-m-d H:i:s'),
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Authentication successful.',
            ]);
        }

        Notice::query()->create([
            'uid' => $user->id,
            'account' => $username,
            'title' => 'Xác thực bị từ chối',
            'content' => 'Ứng dụng xác thực của bạn đã bị quản trị viên từ chối, vui lòng liên hệ với quản trị viên',
            'addtime' => now()->format('Y-m-d H:i:s'),
            'status' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
        ]);
    }

    /**
     * KYC review form data (ThinkPHP User/authrz).
     */
    public function kycForm(int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $user->rzstatus !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'No pending KYC application.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $miners = Kuangji::query()
            ->where('rtype', 2)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get(['id', 'title']);

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'cccd' => $user->cccd,
                'cardzm' => $user->cardzm,
                'cardfm' => $user->cardfm,
                'rztime' => (int) $user->rztime,
                'miners' => $miners,
            ],
        ]);
    }

    public function coins(int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $userCoin = UserCoin::query()->where('userid', $user->id)->first();

        return response()->json([
            'status' => true,
            'data' => $userCoin ? new UserCoinResource($userCoin) : null,
        ]);
    }

    public function agents(ListUsersRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $paginator = User::query()
            ->where('is_agent', 1)
            ->orderByDesc('id')
            ->paginate($perPage);

        $agents = collect($paginator->items());
        $this->enrichAgents($agents);

        return response()->json([
            'status' => true,
            'data' => AgentResource::collection($agents),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function setAgent(int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $user->is_agent === 1) {
            return response()->json([
                'status' => false,
                'message' => 'Already an agent.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$user->update(['is_agent' => 1])) {
            return response()->json([
                'status' => false,
                'message' => 'Setup failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Setup successful.',
        ]);
    }

    public function cancelAgent(int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $user->is_agent === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Not an agent.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$user->update(['is_agent' => 0])) {
            return response()->json([
                'status' => false,
                'message' => 'Setup failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'message' => 'Setup successful.',
        ]);
    }

    /**
     * Login logs (ThinkPHP User/log).
     */
    public function loginLogs(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));
        $status = $request->input('status');

        $query = UserLog::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $userId = User::query()->where('username', $username)->value('id');
            if ($userId) {
                $query->where('userid', $userId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($status !== null && $status !== '') {
            $query->where('status', (int) $status - 1);
        }

        $paginator = $query->paginate($perPage);
        $logs = collect($paginator->items());
        $this->attachUsernamesToLogs($logs);

        return response()->json([
            'status' => true,
            'data' => UserLogResource::collection($logs),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * User wallets (ThinkPHP User/qianbao).
     */
    public function wallets(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));
        $coinname = $request->input('coinname');

        $query = UserQianbao::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $userId = User::query()->where('username', $username)->value('id');
            if ($userId) {
                $query->where('userid', $userId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($coinname !== null && $coinname !== '') {
            $query->where('name', trim($coinname));
        }

        $paginator = $query->paginate($perPage);
        $wallets = collect($paginator->items());
        $this->attachUsernamesToWallets($wallets);

        return response()->json([
            'status' => true,
            'data' => UserWalletResource::collection($wallets),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * All user asset balances (ThinkPHP User/coin).
     */
    public function userAssets(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));

        $query = UserCoin::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $userId = User::query()->where('username', $username)->value('id');
            if ($userId) {
                $query->where('userid', $userId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $adminUsernames = Admin::query()->pluck('username')->all();
        $paginator = $query->paginate($perPage);
        $assets = collect($paginator->items())
            ->filter(function (UserCoin $coin) use ($adminUsernames) {
                $user = User::query()->find($coin->userid);
                return $user && !in_array($user->username, $adminUsernames, true);
            })
            ->values();

        foreach ($assets as $asset) {
            $asset->username = User::query()->where('id', $asset->userid)->value('username');
        }

        return response()->json([
            'status' => true,
            'data' => UserCoinResource::collection($assets),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function showAsset(int $id): JsonResponse
    {
        $asset = UserCoin::query()->find($id);

        if (!$asset) {
            return response()->json([
                'status' => false,
                'message' => 'Asset not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $asset->username = User::query()->where('id', $asset->userid)->value('username');

        return response()->json([
            'status' => true,
            'data' => new UserCoinResource($asset),
        ]);
    }

    public function updateAsset(UpdateUserAssetRequest $request, int $id): JsonResponse
    {
        $asset = UserCoin::query()->find($id);

        if (!$asset) {
            return response()->json([
                'status' => false,
                'message' => 'Asset not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $balances = (array) $request->input('balances', []);
        $payload = [];

        foreach ($balances as $field => $value) {
            if (Schema::hasColumn('tw_user_coin', (string) $field)) {
                $payload[(string) $field] = $value;
            }
        }

        if ($payload === [] || !$asset->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $asset->username = User::query()->where('id', $asset->userid)->value('username');

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new UserCoinResource($asset->fresh()),
        ]);
    }

    public function showWallet(int $id): JsonResponse
    {
        $wallet = UserQianbao::query()->find($id);

        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Wallet not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $wallet->username = User::query()->where('id', $wallet->userid)->value('username');

        return response()->json([
            'status' => true,
            'data' => new UserWalletResource($wallet),
        ]);
    }

    public function storeWallet(UpsertUserWalletRequest $request): JsonResponse
    {
        $userid = (int) $request->input('userid');

        if ($userid <= 0 && $request->filled('username')) {
            $userid = (int) User::query()->where('username', $request->input('username'))->value('id');
        }

        if ($userid <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $wallet = UserQianbao::query()->create([
            'userid' => $userid,
            'name' => $request->input('name'),
            'addr' => $request->input('addr'),
            'czline' => $request->input('czline'),
            'status' => (int) $request->input('status', 1),
            'addtime' => $request->filled('addtime') ? strtotime((string) $request->input('addtime')) : time(),
        ]);

        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $wallet->username = User::query()->where('id', $wallet->userid)->value('username');

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new UserWalletResource($wallet),
        ], Response::HTTP_CREATED);
    }

    public function updateWallet(UpsertUserWalletRequest $request, int $id): JsonResponse
    {
        $wallet = UserQianbao::query()->find($id);

        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Wallet not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only(['name', 'addr', 'czline', 'status']);

        if ($request->filled('addtime')) {
            $payload['addtime'] = strtotime((string) $request->input('addtime'));
        }

        if (!$wallet->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $wallet->username = User::query()->where('id', $wallet->userid)->value('username');

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new UserWalletResource($wallet->fresh()),
        ]);
    }

    public function destroyWallets(Request $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!UserQianbao::query()->whereIn('id', $ids)->delete()) {
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

    public function showLoginLog(int $id): JsonResponse
    {
        $log = UserLog::query()->find($id);

        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => 'Login log not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->attachUsernamesToLogs(collect([$log]));

        return response()->json([
            'status' => true,
            'data' => new UserLogResource($log),
        ]);
    }

    public function updateLoginLog(Request $request, int $id): JsonResponse
    {
        $log = UserLog::query()->find($id);

        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => 'Login log not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->only(['type', 'remark', 'addip', 'addr', 'status']);

        if ($request->filled('addtime')) {
            $payload['addtime'] = strtotime((string) $request->input('addtime'));
        }

        if (!$log->update($payload)) {
            return response()->json([
                'status' => false,
                'message' => 'Edit unsuccessful.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->attachUsernamesToLogs(collect([$log]));

        return response()->json([
            'status' => true,
            'message' => 'Edit successfully.',
            'data' => new UserLogResource($log->fresh()),
        ]);
    }

    public function updateLoginLogStatus(UpdateUserLoginLogStatusRequest $request): JsonResponse
    {
        $ids = $this->normalizeIds($request->input('ids'));
        $type = strtolower((string) $request->input('type'));

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter error.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = UserLog::query()->whereIn('id', $ids);

        if ($type === 'del') {
            if (!$query->delete()) {
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

        $data = match ($type) {
            'forbid' => ['status' => 0],
            'resume' => ['status' => 1],
            'repeal' => ['status' => 2, 'endtime' => time()],
            'delete' => ['status' => -1],
            default => null,
        };

        if ($data === null || !$query->update($data)) {
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

    private function applyUserStatusMutation(array $ids, int $type): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();

        if ($ids === []) {
            return response()->json([
                'status' => false,
                'message' => 'Select a member.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($type === 5 && (int) $admin->level === 1) {
            return response()->json([
                'status' => false,
                'message' => 'No permission to delete member.',
            ], Response::HTTP_FORBIDDEN);
        }

        $users = User::query()->whereIn('id', $ids)->get();
        $result = false;

        foreach ($users as $user) {
            $result = match ($type) {
                1 => $this->freezeUser($user),
                2 => $this->unfreezeUser($user),
                3 => $user->update(['txstate' => 1]),
                4 => $user->update(['txstate' => 2]),
                5 => $user->delete(),
                default => false,
            };

            if (!$result) {
                break;
            }
        }

        if (!$result) {
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

    private function generateInvitCode(): string
    {
        do {
            $code = (string) random_int(100000, 999999);
        } while (User::query()->where('invit', $code)->exists());

        return $code;
    }

    private function freezeUser(User $user): bool
    {
        $updated = $user->update(['status' => 2]);

        if ($updated && $user->username) {
            Admin::query()
                ->where('username', $user->username)
                ->update(['status' => 2]);
        }

        return $updated;
    }

    private function unfreezeUser(User $user): bool
    {
        $updated = $user->update(['status' => 1]);

        if ($updated && $user->username) {
            Admin::query()
                ->where('username', $user->username)
                ->update(['status' => 1]);
        }

        return $updated;
    }

    /**
     * @param Collection<int, User> $users
     */
    private function enrichUsers(Collection $users): void
    {
        if ($users->isEmpty()) {
            return;
        }

        $userIds = $users->pluck('id')->all();
        $invitIds = $users
            ->flatMap(fn (User $user) => [$user->invit_1, $user->invit_2, $user->invit_3])
            ->filter(fn ($id) => $id !== null && $id !== '' && $id !== '0' && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $invitUsernames = User::query()
            ->whereIn('id', $invitIds)
            ->pluck('username', 'id');

        $adminUsernames = Admin::query()
            ->pluck('username')
            ->flip();

        $loginStates = UserLog::query()
            ->whereIn('userid', $userIds)
            ->where(function ($query) {
                $query->where('type', 'login')
                    ->orWhere('type', 'Đăng nhập');
            })
            ->orderByDesc('id')
            ->get()
            ->unique('userid')
            ->keyBy('userid');

        $userCoins = UserCoin::query()
            ->whereIn('userid', $userIds)
            ->get()
            ->keyBy('userid');

        foreach ($users as $user) {
            $user->invit_1_username = $this->resolveInvitUsername($user->invit_1, $invitUsernames);
            $user->invit_2_username = $this->resolveInvitUsername($user->invit_2, $invitUsernames);
            $user->invit_3_username = $this->resolveInvitUsername($user->invit_3, $invitUsernames);
            $user->is_manager = isset($adminUsernames[$user->username]);
            $user->login_state = isset($loginStates[$user->id])
                ? (int) $loginStates[$user->id]->status
                : null;
            $user->user_coin = $userCoins->get($user->id);
        }
    }

    /**
     * @param Collection<int|string, string> $invitUsernames
     */
    private function resolveInvitUsername(mixed $invitId, Collection $invitUsernames): ?string
    {
        if ($invitId === null || $invitId === '' || $invitId === '0') {
            return null;
        }

        return $invitUsernames->get((int) $invitId);
    }

    /**
     * @param Collection<int, User> $agents
     */
    private function enrichAgents(Collection $agents): void
    {
        if ($agents->isEmpty()) {
            return;
        }

        $agentIds = $agents->pluck('id')->all();

        $oneCounts = User::query()
            ->whereIn('invit_1', $agentIds)
            ->selectRaw('invit_1 as agent_id, count(*) as cnt')
            ->groupBy('invit_1')
            ->pluck('cnt', 'agent_id');

        $twoCounts = User::query()
            ->whereIn('invit_2', $agentIds)
            ->selectRaw('invit_2 as agent_id, count(*) as cnt')
            ->groupBy('invit_2')
            ->pluck('cnt', 'agent_id');

        $threeCounts = User::query()
            ->whereIn('invit_3', $agentIds)
            ->selectRaw('invit_3 as agent_id, count(*) as cnt')
            ->groupBy('invit_3')
            ->pluck('cnt', 'agent_id');

        foreach ($agents as $agent) {
            $one = (int) ($oneCounts[$agent->id] ?? 0);
            $two = (int) ($twoCounts[$agent->id] ?? 0);
            $three = (int) ($threeCounts[$agent->id] ?? 0);

            $agent->referral_one = $one;
            $agent->referral_two = $two;
            $agent->referral_three = $three;
            $agent->referral_all = $one + $two + $three;
        }
    }

    /**
     * @param Collection<int, UserLog> $logs
     */
    private function attachUsernamesToLogs(Collection $logs): void
    {
        if ($logs->isEmpty()) {
            return;
        }

        $userIds = $logs->pluck('userid')->unique()->all();
        $usernames = User::query()->whereIn('id', $userIds)->pluck('username', 'id');

        foreach ($logs as $log) {
            $log->username = $usernames->get((int) $log->userid);
        }
    }

    /**
     * @param Collection<int, UserQianbao> $wallets
     */
    private function attachUsernamesToWallets(Collection $wallets): void
    {
        if ($wallets->isEmpty()) {
            return;
        }

        $userIds = $wallets->pluck('userid')->unique()->all();
        $usernames = User::query()->whereIn('id', $userIds)->pluck('username', 'id');

        foreach ($wallets as $wallet) {
            $wallet->username = $usernames->get((int) $wallet->userid);
        }
    }
}
