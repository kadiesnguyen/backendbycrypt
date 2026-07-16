<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Config;
use App\Models\Kjorder;
use App\Models\Kjprofit;
use App\Models\Kuangji;
use App\Models\User;
use App\Models\UserCoin;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrepoolController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get all mining machines
            $alist = Kuangji::where(['status' => 1, 'rtype' => 1])
                ->orderBy('id', 'asc')
                ->get();

            // Get exclusive mining machines
            $blist = Kuangji::where(['status' => 1, 'type' => 1, 'rtype' => 1])
                ->orderBy('id', 'asc')
                ->get();

            // Get shared mining machines
            $clist = Kuangji::where(['status' => 1, 'type' => 2, 'rtype' => 1])
                ->orderBy('id', 'asc')
                ->get();

            if ($user) {
                // Get user's mining orders
                $mylist = Kjorder::where('uid', $user->id)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            // Get config
            $config = Config::where('id', 1)->first(['webkj']);
            $webkj = $config->webkj;

            return response()->json([
                'status' => true,
                'message' => 'Orepool info retrieved successfully',
                'data' => [
                    'overview' => $alist->toArray(),
                    'exclusive' => $blist->toArray(),
                    'shared' => $clist->toArray(),
                    'mylist' => isset($mylist) ? $mylist->toArray() : [],
                    'webkj' => $webkj,
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Orepool info retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve orepool info. Try again later.',
            ], 500);
        }
    }

    public function getMiningMachineInfo(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:tw_kuangji,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Get mining machine info
            $info = Kuangji::findOrFail($request->query('id'));

            // Process sharebl for type=2
            if ($info->type == 2) {
                $typearr = explode('|', $info->sharebl);
                $info->fe1 = $typearr[0] ?? null;
                $info->fe2 = $typearr[1] ?? null;
            }

            return response()->json([
                'status' => true,
                'message' => 'Mining machine info retrieved successfully',
                'data' => $info->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Mining machine info retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve mining machine info. Try again later.',
            ], 500);
        }
    }

    public function normalmin(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Get normalmin
            $normalmin = Kjorder::where('uid', $user->id)
                ->where('status', 1)
                ->whereHas('kuangji', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Normalmin retrieved successfully',
                'data' => $normalmin->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Normalmin retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve normalmin. Try again later.',
            ], 500);
        }
    }

    public function overduemin(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please log in to view your overduemin.',
                ], 401);
            }

            // Get overduemin
            $overduemin = Kjorder::where('uid', $user->id)
                ->where('status', '!=', 1)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Overduemin retrieved successfully',
                'data' => $overduemin->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Overduemin retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve overduemin. Try again later.',
            ], 500);
        }
    }

    public function profitlist(Request $request)
    {
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            $list = Kjprofit::where('uid', $user->id)
                ->orderBy('day', 'desc')
                ->limit(50)
                ->get();

            // Add status based on day
            $nowtime = now()->toDateString();
            foreach ($list as $item) {
                $item->status = $item->day < $nowtime ? 1 : 2;
            }

            return response()->json([
                'status' => true,
                'message' => 'Profit list retrieved successfully',
                'data' => $list->toArray(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Profit list retrieval failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve profit list. Try again later.',
            ], 500);
        }
    }

    public function buyMiningMachine(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:tw_kuangji,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = JWTAuth::user();

            // Check user verification status
            if ($user->rzstatus != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please complete identity verification before purchasing a mining machine.',
                ], 422);
            }
            // Get mining machine info
            $minfo = Kuangji::findOrFail($request->id);

            // Check machine status
            if ($minfo->status != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mining machine is not available for purchase',
                ], 422);
            }

            if ($minfo->sellnum >= $minfo->allnum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mining machine is sold out',
                ], 422);
            }

            // Check purchase limit
            $minecount = Kjorder::where(['kid' => $request->kid, 'uid' => $user->id, 'status' => 1])->count();
            if ($minecount >= $minfo->buymax) {
                return response()->json([
                    'status' => false,
                    'message' => 'Have reached the maximum purchase limit for this mining machine',
                ], 422);
            }

            // Get user coin balance
            $umoney = UserCoin::where('userid', $user->id)->firstOrFail();

            // Check purchase requirements
            if ($minfo->buyask == 1) {
                $ptcoin = 'usdt';
                $ptcoind = $ptcoin . 'd';
                if (($umoney->$ptcoin + $umoney->$ptcoind) < $minfo->asknum) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Minimum balance requirement not met',
                    ], 422);
                }
            } elseif ($minfo->buyask == 2) {
                $tzcount = User::where('invit_1', $user->id)->count();
                if ($tzcount < $minfo->asknum) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Not enough referrals to meet purchase requirements',
                    ], 422);
                }
            }

            // Check balance for purchase
            $pricecoin = $minfo->pricecoin;
            if ($umoney->$pricecoin < $minfo->pricenum) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance to purchase this mining machine',
                ], 422);
            }

            // Begin transaction
            DB::beginTransaction();

            // Create mining order
            $odate = [
                'kid' => $minfo->id,
                'type' => 1,
                'uid' => $user->id,
                'username' => $user->username,
                'kjtitle' => $minfo->title,
                'imgs' => $minfo->imgs,
                'status' => 1,
                'cycle' => $minfo->cycle,
                'synum' => $minfo->cycle,
                'outtype' => $minfo->outtype,
                'outcoin' => $minfo->outcoin,
                'djout' => $minfo->djout,
                'djnum' => $minfo->djout == 2 ? $minfo->djday : $minfo->djday,
                'addtime' => now()->toDateTimeString(),
                'endtime' => now()->addDays($minfo->cycle)->toDateTimeString(),
                'intaddtime' => now()->timestamp,
                'intendtime' => now()->timestamp + (86400 * $minfo->cycle),
            ];

            if ($minfo->outtype == 1) {
                $odate['outnum'] = 0;
                $odate['outusdt'] = $minfo->dayoutnum;
            } elseif ($minfo->outtype == 2) {
                $odate['outnum'] = $minfo->dayoutnum;
                $odate['outusdt'] = 0;
            }

            Kjorder::create($odate);

            // Deduct balance
            $buyprice = $minfo->pricenum;
            $buycoin = $minfo->pricecoin;
            $umoney->decrement($buycoin, $buyprice);

            // Create bill record
            $billdata = [
                'uid' => $user->id,
                'username' => $user->username,
                'num' => $buyprice,
                'coinname' => $buycoin,
                'afternum' => $umoney->$buycoin,
                'type' => 5,
                'addtime' => now()->toDateTimeString(),
                'st' => 2,
                'remark' => 'Buy mining machine: ' . $minfo->title,
            ];
            
            Bill::create($billdata);

            // Handle purchase reward - No ward right after purchasing
            // if ($minfo->jlnum > 0) {
            //     $jlcoin = $minfo->jlcoin;
            //     $jlnum = $minfo->jlnum;
            //     $umoney->increment($jlcoin, $jlnum);

            //     $jlbilldata = [
            //         'uid' => $user->id,
            //         'username' => $user->username,
            //         'num' => $jlnum,
            //         'coinname' => $jlcoin,
            //         'afternum' => $umoney->$jlcoin,
            //         'type' => 6,
            //         'addtime' => now()->toDateTimeString(),
            //         'st' => 1,
            //         'remark' => 'Buy mining machine reward',
            //     ];
            //     Bill::create($jlbilldata);

            //     $minfo->increment('sellnum', 1);
            // }

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Buy mining machine successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Buy mining machine failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Buy mining machine failed. Try again later.',
            ], 500);
        }
    }
}
