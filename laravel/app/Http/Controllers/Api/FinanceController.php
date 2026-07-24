<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Coin;
use App\Models\CoinExchangeHistory;
use App\Models\Config;
use App\Models\Myzc;
use App\Models\Notice;
use App\Support\NotificationTtl;
use App\Models\PerpPosition;
use App\Models\Recharge;
use App\Models\RechargeMethod;
use App\Models\UserCoin;
use App\Models\TransferHistory;
use App\Services\BinanceTickerService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FinanceController extends Controller
{
    public function depositHistory(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            $history = Recharge::where('uid', $user->id)
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get()
                ->map(static function (Recharge $row) {
                    return [
                        'id' => $row->id,
                        'coinname' => strtolower((string) $row->coin),
                        'num' => $row->num,
                        'addtime' => $row->addtime,
                        'status' => (int) $row->status,
                    ];
                })
                ->values()
                ->all();

            return response()->json([
                'status' => true,
                'message' => 'Lấy lịch sử gửi tiền thành công',
                'data' => $history,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('History retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy lịch sử gửi tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function coinList(Request $request)
    {
        try {
            $coins = Coin::where('status', 1)
                // ->where('name', '<>', 'usdt')
                ->orderBy('sort', 'asc')
                ->get();
            return response()->json([
                'status' => true,
                'message' => 'Lấy danh sách tiền thành công',
                'data' => $coins->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Coins retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy danh sách tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function rechargeMethods(Request $request)
    {
        try {
            // ponytail: client renders QR from address; skip server PNG (needs imagick).
            $methods = RechargeMethod::where('status', 1)
                ->get(['id', 'name', 'wallet', 'address', 'coin', 'status']);

            $data = $methods->map(static function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'wallet' => $method->wallet,
                    'address' => $method->address,
                    'coin' => $method->coin,
                    'status' => $method->status,
                    'qrcode_url' => null,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Lấy phương thức nạp tiền thành công',
                'data' => $data->values()->all(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Recharge methods retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy phương thức nạp tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }
    
    public function withdrawHistory(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get history
            $history = Myzc::where('userid', $user->id)
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Lấy lịch sử rút tiền thành công',
                'data' => $history->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('History retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy lịch sử rút tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }
    
    public function withdrawHistoryCancelled(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            $cutoff = NotificationTtl::expiresBefore();
            $history = Myzc::where('userid', $user->id)
                ->where('status', 3)
                ->whereRaw('COALESCE(endtime, addtime) >= ?', [$cutoff])
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Lấy lịch sử rút tiền đã hủy thành công',
                'data' => $history->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('History retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy lịch sử rút tiền đã hủy, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function exchangeHistory(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $page = max((int) $request->input('page', 1), 1);
            $limit = (int) $request->input('limit', 20);
            $limit = max(min($limit, 100), 1);

            $history = CoinExchangeHistory::where('userid', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Lấy lịch sử đổi tiền thành công',
                'data' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'last_page' => $history->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Exchange history retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy lịch sử đổi tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function balance(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get coin list
            $coins = Coin::where('status', 1)
                ->orderBy('sort', 'asc')
                ->get();

            // Get user coin balances
            $userCoin = UserCoin::where('userid', $user->id)->first();

            // Map coins with balances
            $coinBalances = $coins->map(function ($coin) use ($userCoin) {
                $available = 0.00;
                $freeze = 0.00;
                $total = 0.00;

                if ($userCoin) {
                    $column = $coin->name;
                    $frozenColumn = $column . '_d';

                    // Số dư thông thường
                    $available = (float) ($userCoin->$column ?? 0.00);

                    // Số dư bị đóng băng
                    $freeze = (float) ($userCoin->$frozenColumn ?? 0.00);

                    // Tổng số dư
                    $total = $available + $freeze;
                }

                return [
                    'id' => $coin->id,
                    'name' => $coin->name,
                    'title' => $coin->title,
                    'balance' => [
                        // Floor 2dp — never show more withdrawable than DB holds.
                        'available' => number_format(floor($available * 100 + 1e-8) / 100, 2, '.', ''),
                        'freeze' => number_format(floor($freeze * 100 + 1e-8) / 100, 2, '.', ''),
                        'total' => number_format(floor($total * 100 + 1e-8) / 100, 2, '.', ''),
                    ],
                    'deposit_network' => $coin->czline,
                    'addresss' => $coin->czaddress,
                    'deposit_status' => $coin->czstatus,
                    'deposit_min' => $coin->czminnum,
                    // 'deposit_fee_type' => $coin->sxftype,
                    // 'deposit_fee_percent' => $coin->txsxf,
                    // 'deposit_fee_amount' => $coin->txsxf_n,
                    'withdraw_status' => $coin->txstatus,
                    'withdraw_min' => $coin->txminnum,
                    'withdraw_max' => $coin->txmaxnum,
                    'bank' => $coin->bank,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Lấy số dư tiền tệ thành công',
                'data' => $coinBalances->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Lấy số dư tiền tệ thất bại', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy số dư tiền tệ, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function stats(BinanceTickerService $ticker)
    {
        try {
            $user = JWTAuth::user();
            $userCoin = UserCoin::where('userid', $user->id)->first();
            $coins = Coin::where('status', 1)->orderBy('sort', 'asc')->get();

            $spotUsdt = 0.0;
            foreach ($coins as $coin) {
                $column = $coin->name;
                $frozenColumn = $column . '_d';
                $available = (float) ($userCoin->$column ?? 0);
                $freeze = (float) ($userCoin->$frozenColumn ?? 0);
                $amount = $available + $freeze;
                if ($amount <= 0) {
                    continue;
                }

                $rate = $ticker->rateToUsdt((string) $column);
                if ($rate === null || $rate <= 0) {
                    continue;
                }

                $spotUsdt += $amount * $rate;
            }

            $revenue = (float) PerpPosition::where('uid', $user->id)
                ->whereIn('status', [PerpPosition::STATUS_CLOSED, PerpPosition::STATUS_LIQUIDATED])
                ->sum('realized_pnl');

            $spot = number_format($spotUsdt, 2, '.', '');
            $revenueFmt = number_format($revenue, 2, '.', '');

            return response()->json([
                'status' => true,
                'message' => 'Lấy thống kê tài chính thành công',
                'data' => [
                    'estimated_diy' => $spot,
                    'revenue' => $revenueFmt,
                    'holdings' => $spot,
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Finance stats failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy thống kê tài chính, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function submitRecharge(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|gt:0',
                'payimg' => 'required|image|mimes:jpg,jpeg,png|max:16384', // Max 16MB
                'method' => 'required|integer|exists:tw_recharge_method,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();

            $rechargeMethod = RechargeMethod::where('id', $request->method)
                ->where('status', 1)
                ->first();

            if (!$rechargeMethod) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phương thức nạp tiền không hợp lệ hoặc đã bị vô hiệu hóa.',
                ], 422);
            }

            $coinName = strtolower(trim((string) $rechargeMethod->coin));
            $coin = Coin::whereRaw('LOWER(name) = ?', [$coinName])->first();

            if (!$coin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đồng tiền không hợp lệ hoặc đã bị vô hiệu hóa.',
                ], 422);
            }

            if ($coin->czstatus != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gửi tiền đã bị vô hiệu hóa cho loại tiền này.',
                ], 500);
            }

            if ($request->amount < ($coin->czminnum ?? 0)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số tiền nạp thấp hơn mức tối thiểu: ' . ($coin->czminnum ?? 0),
                ], 422);
            }

            // Upload proof image
            $payimgPath = $request->file('payimg')->store('recharge_proofs', 'public');
            $payimgUrl = Storage::disk('public')->url($payimgPath);

            $num_real = $request->amount;
            $address = trim(($rechargeMethod->wallet ? ($rechargeMethod->wallet . ': ') : '') . (string) $rechargeMethod->address);

            // Create recharge record
            $data = [
                'method' => $rechargeMethod->id,
                'uid' => $user->id,
                'username' => $user->username,
                'coin' => strtoupper($coin->name),
                'num' => $request->amount,
                'num_real' => $num_real,
                'address' => $address,
                'addtime' => now()->toDateTimeString(),
                'updatetime' => now()->toDateTimeString(),
                'status' => 1,
                'payimg' => $payimgUrl,
                'msg' => '',
            ];

            $recharge = Recharge::create($data);

            if ($recharge) {
                $coinLabel = strtoupper((string) $coin->name);
                $amountLabel = rtrim(rtrim(number_format((float) $request->amount, 8, '.', ''), '0'), '.');
                $when = now()->format('Y-m-d H:i:s');
                Notice::query()->create([
                    'uid' => $user->id,
                    'account' => $user->username,
                    'title' => 'Nạp ' . $coinLabel . ' đang xử lý',
                    'content' => 'Yêu cầu nạp ' . $amountLabel . ' ' . $coinLabel
                        . ' lúc ' . $when . ' (UTC) đang được xử lý. Vui lòng liên hệ chăm sóc khách hàng để được duyệt sớm. Nếu bạn không nhận ra hoạt động này, hãy liên hệ ngay.',
                    'addtime' => $when,
                    'status' => 1,
                    'user_view' => 1,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Gửi chứng nhận thành công, đang chờ xử lý.',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Gửi chứng nhận thất bại. Vui lòng thử lại sau.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Recharge submission failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Gửi chứng nhận thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function submitWithdraw(Request $request)
    {
        try {
            $user = JWTAuth::user();

            if ($user->rzstatus != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng hoàn thành xác minh danh tính trước khi rút tiền.',
                ], 422);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'cid' => 'required|integer|exists:tw_coin,id',
                'amount' => 'required|numeric|gt:0',
                'address' => 'nullable|string|max:255',
                'wallet' => 'nullable|string|max:50',
                'network' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $cryptoAddress = trim((string) $request->input('address', ''));
            $network = trim((string) ($request->input('wallet') ?: $request->input('network') ?: ''));

            // Crypto withdraw uses address+network; otherwise require linked bank.
            if ($cryptoAddress === '') {
                if (empty($user->bank_name) || empty($user->bank_acc_no) || empty($user->bank_acc_name)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vui lòng liên kết tài khoản ngân hàng trước khi rút tiền.',
                    ], 422);
                }
            }

            // Get coin info
            $coin = Coin::findOrFail($request->cid);

            if ($cryptoAddress !== '' && $network !== '' && !empty($coin->czline)) {
                $allowed = collect(preg_split('/[,|\/]/', (string) $coin->czline) ?: [])
                    ->map(static fn ($n) => strtoupper(trim((string) $n)))
                    ->filter()
                    ->values();
                if ($allowed->isNotEmpty() && !$allowed->contains(strtoupper($network))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mạng rút không hợp lệ cho đồng tiền này.',
                    ], 422);
                }
            }

            // Bank FX rate only required for bank withdrawals
            if ($cryptoAddress === '' && (!isset($coin->bank) || $coin->bank <= 0)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể lấy tỷ giá cho đồng tiền này. Rút tiền không khả dụng.',
                ], 422);
            }

            // Get user coin balance
            $userCoin = UserCoin::where('userid', $user->id)->first();
            if (!$userCoin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư tiền tệ của người dùng không tìm thấy',
                ], 422);
            }

            $coinname = $coin->name;
            // decimal:N cast returns strings — always compare as floats.
            $available = (float) ($userCoin->{$coinname} ?? 0);
            $amount = (float) $request->amount;

            // UI balance is 2dp; "Max" can be up to ~0.01 above true balance after round-half-up.
            if ($amount > $available && round($amount, 2) <= round($available, 2)) {
                $amount = $available;
            }

            // Check withdrawal limits
            if ($amount < (float) $coin->txminnum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể rút ít hơn số tiền tối thiểu: ' . $coin->txminnum,
                ], 422);
            }

            if ((float) $coin->txmaxnum > 0 && $amount > (float) $coin->txmaxnum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể rút nhiều hơn số tiền tối đa: ' . $coin->txmaxnum,
                ], 422);
            }

            // Fee = amount × txsxf%. Receive = amount − fee (e.g. 1000 − 0.15).
            // Admin stores percent figure: 0.015 => 0.015%.
            $feePercent = (float) ($coin->txsxf ?? 0);
            $fee = $feePercent > 0 ? ($amount * $feePercent / 100) : 0;
            $num_real = max($amount - $fee, 0);
            $total_needed = $amount;

            if (round($available, 8) < round($total_needed, 8)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư không đủ. Cần: ' . rtrim(rtrim(number_format($total_needed, 8, '.', ''), '0'), '.'),
                ], 422);
            }

            $isCrypto = $cryptoAddress !== '';
            $walletLabel = $isCrypto
                ? ($network !== '' ? strtoupper($network) : 'CRYPTO')
                : 'BANK';
            $withdrawAddress = $isCrypto
                ? $cryptoAddress
                : ($user->bank_name . ' - ' . $user->bank_acc_no . ' - ' . $user->bank_acc_name);

            // Convert to VND for admin approval (bank rate; crypto keeps amount as mum fallback)
            $bankRate = (float) ($coin->bank ?? 1);
            $num_real_vnd = $isCrypto ? $num_real : ($num_real * $bankRate);

            // Start database transaction
            DB::beginTransaction();

            // Debit full requested amount; fee is taken from the payout.
            $decRe = UserCoin::where('userid', $user->id)->decrement($coinname, $total_needed);

            // Create withdrawal record
            $myzcData = [
                'userid' => $user->id,
                'username' => $user->username,
                'wallet' => $walletLabel,
                'coinname' => $coinname,
                'num' => $amount,
                'fee' => $fee,
                'mum' => $num_real_vnd,
                'address' => $withdrawAddress,
                'sort' => 1,
                'addtime' => now()->toDateTimeString(),
                'endtime' => now()->toDateTimeString(),
                'status' => 1,
            ];
            $myzc = Myzc::create($myzcData);

            // Create bill record
            $remark = $isCrypto
                ? ('Withdrawal ' . $walletLabel . ': ' . $cryptoAddress . ' (Amount: ' . $amount . ', Fee: ' . $fee . ', Receive: ' . $num_real . ')')
                : ('Withdrawal to bank: ' . $user->bank_name . ' (Amount: ' . $amount . ', Fee: ' . $fee . ', Receive: ' . $num_real . ')');
            $billData = [
                'uid' => $user->id,
                'username' => $user->username,
                'num' => $total_needed,
                'coinname' => $coinname,
                'afternum' => $available - $total_needed,
                'type' => 2,
                'addtime' => now()->toDateTimeString(),
                'st' => 2,
                'remark' => $remark,
            ];
            $bill = Bill::create($billData);

            if ($decRe && $myzc && $bill) {
                $coinLabel = strtoupper((string) $coinname);
                $amountLabel = rtrim(rtrim(number_format($amount, 8, '.', ''), '0'), '.');
                $when = now()->format('Y-m-d H:i:s');
                Notice::query()->create([
                    'uid' => $user->id,
                    'account' => $user->username,
                    'title' => 'Rút ' . $coinLabel . ' đang xử lý',
                    'content' => 'Yêu cầu rút ' . $amountLabel . ' ' . $coinLabel
                        . ' lúc ' . $when . ' (UTC) đang được xử lý. Vui lòng liên hệ chăm sóc khách hàng để được duyệt sớm. Nếu bạn không nhận ra hoạt động này, hãy liên hệ ngay.',
                    'addtime' => $when,
                    'status' => 1,
                    'user_view' => 1,
                ]);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Rút tiền đã được gửi thành công, đang chờ xử lý.',
                    'data' => [
                        'amount' => $amount,
                        'fee' => $fee,
                        'fee_percent' => $feePercent,
                        'amount_received' => $num_real,
                        'amount_received_vnd' => $num_real_vnd,
                        'exchange_rate' => $bankRate,
                        'coin' => $coinname,
                        'wallet' => $walletLabel,
                        'network' => $network,
                        'address' => $withdrawAddress,
                        'bank_name' => $user->bank_name,
                        'bank_acc_no' => $user->bank_acc_no,
                        'bank_acc_name' => $user->bank_acc_name,
                    ],
                ], 200);
            }

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Rút tiền thất bại. Vui lòng thử lại sau.',
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Withdrawal submission failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Rút tiền thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function transfer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:tw_coin,id',
                'amount' => 'required|numeric|gt:0',
                'from' => 'required|string|max:255',
                'to' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();
            $coin = Coin::findOrFail($request->id);

            $transferHistory = TransferHistory::create([
                'userid' => $user->id,
                'username' => $user->username,
                'coinid' => $coin->id,
                'coinname' => $coin->name,
                'amount' => $request->amount,
                'from' => trim($request->from),
                'to' => trim($request->to),
                'addtime' => now()->toDateTimeString(),
                'status' => 1,
            ]);

            if ($transferHistory) {
                return response()->json([
                    'status' => true,
                    'message' => 'Chuyển tiền thành công, đang chờ xử lý.',
                    'data' => [
                        'coin_id' => $coin->id,
                        'coin' => $coin->name,
                        'amount' => (string) $request->amount,
                        'from' => trim($request->from),
                        'to' => trim($request->to),
                        'record_id' => $transferHistory->id,
                    ],
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Chuyển tiền thất bại. Vui lòng thử lại sau.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Transfer record failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Chuyển tiền thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function transferHistory(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $page = max((int) $request->input('page', 1), 1);
            $limit = (int) $request->input('limit', 20);
            $limit = max(min($limit, 100), 1);

            $history = TransferHistory::where('userid', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Lịch sử chuyển tiền được lấy thành công',
                'data' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'last_page' => $history->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Transfer history retrieval failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lấy lịch sử chuyển tiền, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function quote(Request $request, BinanceTickerService $ticker)
    {
        $resolved = $this->resolveExchangeQuote($request, $ticker, false);

        if ($resolved instanceof JsonResponse) {
            return $resolved;
        }

        return response()->json([
            'status' => true,
            'message' => 'Lấy báo giá thành công',
            'data' => [
                'from' => $resolved['from'],
                'to' => $resolved['to'],
                'amount' => number_format($resolved['amount'], 8, '.', ''),
                'received' => number_format($resolved['receive_amount'], 8, '.', ''),
                'from_rate_usdt' => number_format($resolved['from_rate'], 8, '.', ''),
                'to_rate_usdt' => number_format($resolved['to_rate'], 8, '.', ''),
                'fee' => '0',
            ],
        ], 200);
    }

    public function exchange(Request $request, BinanceTickerService $ticker)
    {
        try {
            $resolved = $this->resolveExchangeQuote($request, $ticker, true);

            if ($resolved instanceof JsonResponse) {
                return $resolved;
            }

            $user = $resolved['user'];
            $from = $resolved['from'];
            $to = $resolved['to'];
            $amount = $resolved['amount'];
            $fromRate = $resolved['from_rate'];
            $toRate = $resolved['to_rate'];
            $usdtAmount = $resolved['usdt_amount'];
            $receiveAmount = $resolved['receive_amount'];

            DB::beginTransaction();

            UserCoin::where('userid', $user->id)->decrement($from, $amount);
            UserCoin::where('userid', $user->id)->increment($to, $receiveAmount);

            $updatedUserCoin = UserCoin::where('userid', $user->id)->first();

            CoinExchangeHistory::create([
                'userid' => $user->id,
                'username' => $user->username,
                'from_coin' => $from,
                'to_coin' => $to,
                'from_amount' => $amount,
                'to_amount' => $receiveAmount,
                'from_rate_usdt' => $fromRate,
                'to_rate_usdt' => $toRate,
                'usdt_amount' => $usdtAmount,
                'addtime' => now()->toDateTimeString(),
                'status' => 1,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Đổi tiền thành công',
                'data' => [
                    'from' => $from,
                    'to' => $to,
                    'amount' => number_format($amount, 8, '.', ''),
                    'received' => number_format($receiveAmount, 8, '.', ''),
                    'fee' => '0',
                    'balance' => [
                        $from => number_format((float) ($updatedUserCoin->$from ?? 0), 8, '.', ''),
                        $to => number_format((float) ($updatedUserCoin->$to ?? 0), 8, '.', ''),
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Exchange failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Đổi tiền thất bại. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    /**
     * @return array<string, mixed>|JsonResponse
     */
    private function resolveExchangeQuote(Request $request, BinanceTickerService $ticker, bool $requireBalance)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string|exists:tw_coin,name',
            'to' => 'required|string|exists:tw_coin,name|different:from',
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $from = strtolower(trim((string) $request->from));
        $to = strtolower(trim((string) $request->to));
        $amount = (float) $request->amount;

        if ($from !== 'usdt' && $to !== 'usdt') {
            return response()->json([
                'status' => false,
                'message' => 'Hiện tại chỉ hỗ trợ đổi tiền qua USDT. Vui lòng chọn một trong hai đồng tiền là USDT.',
            ], 422);
        }

        if (!Schema::hasColumn('tw_user_coin', $from) || !Schema::hasColumn('tw_user_coin', $to)) {
            return response()->json([
                'status' => false,
                'message' => 'Cột số dư tiền không tồn tại',
            ], 422);
        }

        $fromCoin = Coin::where('name', $from)->where('status', 1)->first();
        $toCoin = Coin::where('name', $to)->where('status', 1)->first();

        if (!$fromCoin || !$toCoin) {
            return response()->json([
                'status' => false,
                'message' => 'Đồng tiền không hợp lệ hoặc đã bị vô hiệu hóa.',
            ], 422);
        }

        $user = JWTAuth::user();

        if ($requireBalance) {
            $userCoin = UserCoin::where('userid', $user->id)->first();

            if (!$userCoin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư tiền của người dùng không tồn tại',
                ], 422);
            }

            if ((float) ($userCoin->$from ?? 0) < $amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư không đủ',
                ], 422);
            }
        }

        if (!$ticker->isTickerConfigured()) {
            return response()->json([
                'status' => false,
                'message' => 'Nguồn tỷ giá đổi tiền không được cấu hình. Vui lòng liên hệ quản trị viên.',
            ], 500);
        }

        $fromRate = $ticker->rateToUsdt($from);
        $toRate = $ticker->rateToUsdt($to);

        if (!$fromRate || !$toRate) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể lấy tỷ giá cho một hoặc cả hai đồng tiền. Vui lòng thử lại sau.',
            ], 422);
        }

        $usdtAmount = $amount * $fromRate;
        $receiveAmount = BinanceTickerService::receiveAmount($amount, $fromRate, $toRate);

        return [
            'user' => $user,
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'from_rate' => $fromRate,
            'to_rate' => $toRate,
            'usdt_amount' => $usdtAmount,
            'receive_amount' => $receiveAmount,
        ];
    }
}
