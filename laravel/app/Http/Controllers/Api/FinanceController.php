<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Coin;
use App\Models\CoinExchangeHistory;
use App\Models\Config;
use App\Models\Myzc;
use App\Support\NotificationTtl;
use App\Models\Recharge;
use App\Models\RechargeMethod;
use App\Models\UserCoin;
use App\Models\TransferHistory;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class FinanceController extends Controller
{
    public function depositHistory(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get history
            $history = Bill::where('uid', $user->id)
                ->where('type', 17)
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Lấy lịch sử gửi tiền thành công',
                'data' => $history->toArray(),
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
            $methods = RechargeMethod::where('status', 1)
                ->get(['id', 'name', 'wallet', 'address', 'coin', 'status']);

            Storage::disk('public')->makeDirectory('qrcodes/recharge-methods');

            $data = $methods->map(function ($method) {
                $address = trim((string) ($method->address ?? ''));
                $qrcodeUrl = null;

                if ($address !== '') {
                    $qrFileName = $method->id . '-' . md5($address) . '.png';
                    $qrCodePath = 'qrcodes/recharge-methods/' . $qrFileName;

                    QrCode::format('png')->size(220)->errorCorrection('H')->generate($address, storage_path('app/public/' . $qrCodePath));
                    $qrcodeUrl = Storage::disk('public')->url($qrCodePath);
                }

                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'wallet' => $method->wallet,
                    'address' => $method->address,
                    'coin' => $method->coin,
                    'status' => $method->status,
                    'qrcode_url' => $qrcodeUrl,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Lấy phương thức nạp tiền thành công',
                'data' => $data->toArray(),
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
                        'available' => number_format($available, 2, '.', ''),
                        'freeze' => number_format($freeze, 2, '.', ''),
                        'total' => number_format($total, 2, '.', ''),
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

            if ($user->txstate != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng thêm mật khẩu thanh toán trước khi rút tiền.',
                ], 422);
            }

            if ($user->rzstatus != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng hoàn thành xác minh danh tính trước khi rút tiền.',
                ], 422);
            }

            // Check if user has linked bank account
            if (empty($user->bank_name) || empty($user->bank_acc_no) || empty($user->bank_acc_name)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng liên kết tài khoản ngân hàng trước khi rút tiền.',
                ], 422);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'cid' => 'required|integer|exists:tw_coin,id',
                'amount' => 'required|numeric|gt:0',
                'paypassword' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Verify paypassword
            if (!$user->verifyPaypassword($request->paypassword)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sai mật khẩu thanh toán',
                ], 422);
            }

            $user->repairPaypasswordIfLegacy($request->paypassword);

            // Get coin info
            $coin = Coin::findOrFail($request->cid);

            // Check if bank exchange rate is available
            if (!isset($coin->bank) || $coin->bank <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể lấy tỷ giá cho đồng tiền này. Rút tiền không khả dụng.',
                ], 422);
            }

            // Check withdrawal limits
            if ($request->amount < $coin->txminnum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể rút ít hơn số tiền tối thiểu: ' . $coin->txminnum,
                ], 422);
            }

            if ($request->amount > $coin->txmaxnum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể rút nhiều hơn số tiền tối đa: ' . $coin->txmaxnum,
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

            // Calculate withdrawal fee first (bbsxf is in decimal format: 0.02 = 2%)
            $feeRate = (float) ($coin->bbsxf ?? 0);
            $fee = $feeRate > 0 ? ($request->amount * $feeRate) : 0;
            $total_needed = $request->amount + $fee;

            // Check if user has enough balance including fee
            if ($userCoin->$coinname < $total_needed) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư không đủ. Cần: ' . $total_needed . ' (Rút: ' . $request->amount . ' + Phí: ' . $fee . ')',
                ], 422);
            }

            $bankInfo = $user->bank_name . ' - ' . $user->bank_acc_no . ' - ' . $user->bank_acc_name;
            $num_real = $request->amount;

            // Convert to VND for admin approval
            $bankRate = (float) ($coin->bank ?? 1);
            $num_real_vnd = $num_real * $bankRate;

            // Start database transaction
            DB::beginTransaction();

            // Decrease user coin balance (including fee)
            $decRe = UserCoin::where('userid', $user->id)->decrement($coinname, $total_needed);

            // Create withdrawal record
            $myzcData = [
                'userid' => $user->id,
                'username' => $user->username,
                'wallet' => 'BANK',
                'coinname' => $coinname,
                'num' => $request->amount,
                'fee' => $fee,
                'mum' => $num_real_vnd,
                'address' => $bankInfo,
                'sort' => 1,
                'addtime' => now()->toDateTimeString(),
                'endtime' => now()->toDateTimeString(),
                'status' => 1,
            ];
            $myzc = Myzc::create($myzcData);

            // Create bill record
            $billData = [
                'uid' => $user->id,
                'username' => $user->username,
                'num' => $total_needed,
                'coinname' => $coinname,
                'afternum' => $userCoin->$coinname - $total_needed,
                'type' => 2,
                'addtime' => now()->toDateTimeString(),
                'st' => 2,
                'remark' => 'Withdrawal to bank: ' . $user->bank_name . ' (Amount: ' . $request->amount . ', Fee: ' . $fee . ')',
            ];
            $bill = Bill::create($billData);

            if ($decRe && $myzc && $bill) {
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Rút tiền đã được gửi thành công, đang chờ xử lý.',
                    'data' => [
                        'amount' => $request->amount,
                        'fee' => $fee,
                        'fee_rate' => $feeRate,
                        'amount_received' => $num_real,
                        'amount_received_vnd' => $num_real_vnd,
                        'exchange_rate' => $bankRate,
                        'coin' => $coinname,
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

    public function exchange(Request $request)
    {
        try {
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

            $from = strtolower(trim($request->from));
            $to = strtolower(trim($request->to));
            $amount = (float) $request->amount;

            if ($from !== 'usdt' && $to !== 'usdt') {
                return response()->json([
                    'status' => false,
                    'message' => 'Hiện tại chỉ hỗ trợ đổi tiền qua USDT. Vui lòng chọn một trong hai đồng tiền là USDT.',
                ], 422);
            }

            $user = JWTAuth::user();
            $userCoin = UserCoin::where('userid', $user->id)->first();

            if (!$userCoin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư tiền của người dùng không tồn tại',
                ], 422);
            }

            if (!Schema::hasColumn('tw_user_coin', $from) || !Schema::hasColumn('tw_user_coin', $to)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cột số dư tiền không tồn tại',
                ], 422);
            }

            $fromCoin = Coin::where('name', $from)->first();
            $toCoin = Coin::where('name', $to)->first();

            if (!$fromCoin || !$toCoin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đồng tiền không hợp lệ hoặc đã bị vô hiệu hóa.',
                ], 422);
            }

            if ((float) ($userCoin->$from ?? 0) < $amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Số dư không đủ',
                ], 422);
            }

            $tickerUrlTemplate = config('services.binance.ticker_url');

            if (!$tickerUrlTemplate) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nguồn tỷ giá đổi tiền không được cấu hình. Vui lòng liên hệ quản trị viên.',
                ], 500);
            }

            $fetchRateToUsdt = function (string $coinName) use ($tickerUrlTemplate) {
                if ($coinName === 'usdt') {
                    return 1.0;
                }

                $symbol = strtoupper($coinName);
                $url = str_replace('{symbol}', $symbol, $tickerUrlTemplate);
                $response = Http::timeout(10)->get($url);

                if (!$response->ok()) {
                    return null;
                }

                $price = (float) $response->json('price');

                return $price > 0 ? $price : null;
            };

            $fromRate = $fetchRateToUsdt($from);
            $toRate = $fetchRateToUsdt($to);

            if (!$fromRate || !$toRate) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể lấy tỷ giá cho một hoặc cả hai đồng tiền. Vui lòng thử lại sau.',
                ], 422);
            }

            $usdtAmount = $amount * $fromRate;
            $receiveAmount = $usdtAmount / $toRate;

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
}
