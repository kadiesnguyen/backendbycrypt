<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckinController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\IssueController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrepoolController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TradeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------
| API Routes
|------------------------------------------------
*/
// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('send-verification-code', [AuthController::class, 'sendVerificationCode']);
Route::get('config', [SettingController::class, 'config']);
Route::group(['prefix' => 'orepool'], function () {
  Route::get('', [OrepoolController::class, 'index']);
  Route::get('info', [OrepoolController::class, 'getMiningMachineInfo']);
});
Route::group(['prefix' => 'contract'], function () {
  Route::get('coin', [ContractController::class, 'coinList']);
  Route::get('price', [ContractController::class, 'getPrice']);
  Route::get('prices', [ContractController::class, 'getAllPrices']);
  Route::get('settings', [ContractController::class, 'settings']);
});
Route::group(['prefix' => 'issue'], function () {
  Route::get('list', [IssueController::class, 'getList']);
});
Route::group(['prefix' => 'finance'], function () {
  Route::get('/coins', [FinanceController::class, 'coinList']);
});
Route::group(['prefix' => 'news'], function () {
  Route::get('list', [NewsController::class, 'list']);
  Route::get('{id}', [NewsController::class, 'detail']);
});

// Protected routes
Route::middleware(['auth:api', 'check.user.status'])->group(function () {
  /* User routes */
  Route::group(['prefix' => 'user'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('bills', [UserController::class, 'bills']);
    Route::get('notices', [UserController::class, 'notices']);
    Route::get('/notices/{id}', [UserController::class, 'getNoticeDetail']);
    Route::post('/notices/mark-all-read', [UserController::class, 'markAllNoticesRead']);
    Route::get('referral', [UserController::class, 'referral']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('change-paypassword', [AuthController::class, 'changePayPassword']);
    Route::post('verify-account', [UserController::class, 'verifyAccount']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    Route::post('update-bank', [UserController::class, 'updateUserBankInfo']);
  });
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('transfer', [FinanceController::class, 'transfer']);
  Route::get('transfer/history', [FinanceController::class, 'transferHistory']);
  /* Lệnh giao dịch tiền tệ */
  Route::group(['prefix' => 'trade'], function () {
    Route::post('order', [TradeController::class, 'placeOrder']);
    Route::get('open-orders', [TradeController::class, 'openOrders']);
    Route::get('order-history', [TradeController::class, 'orderHistory']);
    Route::post('order/{id}/cancel', [TradeController::class, 'cancelOrder']);
    Route::get('bborder', [TradeController::class, 'bborder']);
    Route::get('bbhistoryorder', [TradeController::class, 'bbhistoryorder']);
  });
  Route::group(['prefix' => 'contract'], function () {
    Route::get('contractjc', [ContractController::class, 'contractjc']);
    Route::get('progress-contract', [ContractController::class, 'contractProgress']);
    Route::get('contractpc', [ContractController::class, 'contractpc']);
    Route::post('create-order', [ContractController::class, 'createOrder']);
    Route::get('check-order', [ContractController::class, 'checkOrder']);
  });
  Route::group(['prefix' => 'orepool'], function () {
    Route::get('normalmin', [OrepoolController::class, 'normalmin']);
    Route::get('overduemin', [OrepoolController::class, 'overduemin']);
    Route::get('profitlist', [OrepoolController::class, 'profitlist']);
    Route::post('buy-mining-machine', [OrepoolController::class, 'buyMiningMachine']);
  });
  Route::group(['prefix' => 'issue'], function () {
    Route::get('normalissue', [IssueController::class, 'normalissue']);
    Route::get('overdueissue', [IssueController::class, 'overdueissue']);
    Route::get('details', [IssueController::class, 'details']);
    Route::get('me', [IssueController::class, 'me']);
    Route::post('subscribe', [IssueController::class, 'subscribeIssue']);
  });
  Route::group(['prefix' => 'finance'], function () {
    Route::post('/exchange', [FinanceController::class, 'exchange']);
    Route::group(['prefix' => 'deposit'], function () {
      Route::get('history', [FinanceController::class, 'depositHistory']);
      Route::post('/', [FinanceController::class, 'submitRecharge']);
      Route::get('methods', [FinanceController::class, 'rechargeMethods']);
    });
    Route::get('/exchange/history', [FinanceController::class, 'exchangeHistory']);
    Route::group(['prefix' => 'withdraw'], function () {
      Route::get('history', [FinanceController::class, 'withdrawHistory']);
      Route::get('history/cancelled', [FinanceController::class, 'withdrawHistoryCancelled']);
      Route::post('/', [FinanceController::class, 'submitWithdraw']);
    });
    Route::group(['prefix' => 'balance'], function () {
      Route::get('/', [FinanceController::class, 'balance']);
    });
  });
  Route::group(['prefix' => 'checkin'], function () {
    Route::post('/', [CheckinController::class, 'checkin']);
    Route::get('/history', [CheckinController::class, 'history']);
  });
});