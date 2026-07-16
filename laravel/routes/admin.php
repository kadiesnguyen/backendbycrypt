<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CoinController;
use App\Http\Controllers\Admin\ContractOrderController;
use App\Http\Controllers\Admin\ContractQueueController;
use App\Http\Controllers\Admin\ContractSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\CtMarketController;
use App\Http\Controllers\Admin\DepositPortController;
use App\Http\Controllers\Admin\FooterNavigationController;
use App\Http\Controllers\Admin\FrontendNavigationController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\PlatformMarketController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\TrialOrderController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MinerController;
use App\Http\Controllers\Admin\MiningPoolController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\OnlineSupportController;
use App\Http\Controllers\Admin\SpotOrderController;
use App\Http\Controllers\Admin\SiteConfigController;
use App\Http\Controllers\Admin\SpotSettingController;
use App\Http\Controllers\Admin\TradingMarketController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:admin', 'check.admin.status'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('auth/password', [AuthController::class, 'changePassword']);
    Route::get('menus', [MenuController::class, 'index']);
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('pending-counts', [DashboardController::class, 'pendingCounts']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show'])->whereNumber('id');
    Route::put('users/{id}', [UserController::class, 'update'])->whereNumber('id');
    Route::put('users/status', [UserController::class, 'bulkUpdateStatus']);
    Route::put('users/{id}/status', [UserController::class, 'updateStatus'])->whereNumber('id');
    Route::put('users/{id}/trade-lock', [UserController::class, 'updateTradeLock'])->whereNumber('id');
    Route::post('users/{id}/add-funds', [UserController::class, 'addFunds'])->whereNumber('id');
    Route::post('users/{id}/kyc-review', [UserController::class, 'kycReview'])->whereNumber('id');
    Route::get('users/{id}/kyc-form', [UserController::class, 'kycForm'])->whereNumber('id');
    Route::get('users/{id}/coins', [UserController::class, 'coins'])->whereNumber('id');
    Route::put('users/{id}/agent', [UserController::class, 'setAgent'])->whereNumber('id');
    Route::delete('users/{id}/agent', [UserController::class, 'cancelAgent'])->whereNumber('id');

    Route::get('agents', [UserController::class, 'agents']);
    Route::get('user-login-logs', [UserController::class, 'loginLogs']);
    Route::get('user-login-logs/{id}', [UserController::class, 'showLoginLog'])->whereNumber('id');
    Route::put('user-login-logs/{id}', [UserController::class, 'updateLoginLog'])->whereNumber('id');
    Route::put('user-login-logs/status', [UserController::class, 'updateLoginLogStatus']);
    Route::get('user-wallets', [UserController::class, 'wallets']);
    Route::post('user-wallets', [UserController::class, 'storeWallet']);
    Route::get('user-wallets/{id}', [UserController::class, 'showWallet'])->whereNumber('id');
    Route::put('user-wallets/{id}', [UserController::class, 'updateWallet'])->whereNumber('id');
    Route::delete('user-wallets', [UserController::class, 'destroyWallets']);
    Route::get('user-assets', [UserController::class, 'userAssets']);
    Route::get('user-assets/{id}', [UserController::class, 'showAsset'])->whereNumber('id');
    Route::put('user-assets/{id}', [UserController::class, 'updateAsset'])->whereNumber('id');

    Route::get('admins', [AdminController::class, 'index']);
    Route::post('admins', [AdminController::class, 'store']);
    Route::put('admins/status', [AdminController::class, 'updateStatus']);
    Route::get('admins/{id}', [AdminController::class, 'show'])->whereNumber('id');
    Route::put('admins/{id}', [AdminController::class, 'update'])->whereNumber('id');

    Route::get('notices', [NoticeController::class, 'index']);
    Route::get('notices/send/{userId}/{type}', [NoticeController::class, 'sendForm'])
        ->whereNumber('userId')
        ->whereNumber('type');
    Route::post('notices', [NoticeController::class, 'store']);
    Route::delete('notices', [NoticeController::class, 'destroy']);

    Route::get('news', [NewsController::class, 'index']);
    Route::post('news/upload-image', [NewsController::class, 'uploadImage']);
    Route::post('news/upload-cover', [NewsController::class, 'uploadCover']);
    Route::get('news/{id}', [NewsController::class, 'show'])->whereNumber('id');
    Route::post('news', [NewsController::class, 'store']);
    Route::put('news/{id}', [NewsController::class, 'update'])->whereNumber('id');
    Route::delete('news', [NewsController::class, 'destroy']);

    Route::get('articles', [ArticleController::class, 'index']);
    Route::post('articles/upload-image', [ArticleController::class, 'uploadImage']);
    Route::post('articles/upload-cover', [ArticleController::class, 'uploadCover']);
    Route::get('articles/{id}', [ArticleController::class, 'show'])->whereNumber('id');
    Route::post('articles', [ArticleController::class, 'store']);
    Route::put('articles/{id}', [ArticleController::class, 'update'])->whereNumber('id');
    Route::delete('articles', [ArticleController::class, 'destroy']);

    Route::get('deposits', [DepositController::class, 'index']);
    Route::post('deposits/{id}/approve', [DepositController::class, 'approve'])->whereNumber('id');
    Route::post('deposits/{id}/reject', [DepositController::class, 'reject'])->whereNumber('id');
    Route::delete('deposits/{id}', [DepositController::class, 'destroy'])->whereNumber('id');

    Route::get('withdrawals', [WithdrawalController::class, 'index']);
    Route::post('withdrawals/{id}/approve', [WithdrawalController::class, 'approve'])->whereNumber('id');
    Route::post('withdrawals/{id}/reject', [WithdrawalController::class, 'reject'])->whereNumber('id');
    Route::delete('withdrawals/{id}', [WithdrawalController::class, 'destroy'])->whereNumber('id');

    Route::get('transfers', [TransferController::class, 'index']);

    Route::get('bills', [BillController::class, 'index']);
    Route::delete('bills', [BillController::class, 'destroy']);

    Route::get('contract-orders', [ContractOrderController::class, 'index']);
    Route::get('contract-orders/closed', [ContractOrderController::class, 'closed']);
    Route::get('contract-orders/pending-count', [ContractOrderController::class, 'pendingCount']);
    Route::post('contract-orders/mark-notified', [ContractOrderController::class, 'markNotified']);
    Route::put('contract-orders/win-loss', [ContractOrderController::class, 'setWinLoss']);
    Route::post('contract-orders/{id}/settle', [ContractOrderController::class, 'manualSettle'])->whereNumber('id');
    Route::post('contract-orders/settle-stuck', [ContractOrderController::class, 'settleStuck']);

    Route::get('trial-orders', [TrialOrderController::class, 'index']);

    Route::get('contract-queue', [ContractQueueController::class, 'index']);
    Route::post('contract-queue/action', [ContractQueueController::class, 'action']);
    Route::put('contract-queue/{id}', [ContractQueueController::class, 'update'])->whereNumber('id');
    Route::delete('contract-queue/{id}', [ContractQueueController::class, 'destroy'])->whereNumber('id');

    Route::get('contract-settings', [ContractSettingController::class, 'show']);
    Route::put('contract-settings', [ContractSettingController::class, 'update']);

    Route::get('contract-markets', [TradingMarketController::class, 'index']);
    Route::get('contract-markets/form-meta', [TradingMarketController::class, 'formMeta']);
    Route::get('contract-markets/{id}', [TradingMarketController::class, 'show'])->whereNumber('id');
    Route::post('contract-markets', [TradingMarketController::class, 'store']);
    Route::put('contract-markets/{id}', [TradingMarketController::class, 'update'])->whereNumber('id');

    Route::get('spot-orders/market', [SpotOrderController::class, 'marketOrders']);
    Route::get('spot-orders/limit', [SpotOrderController::class, 'limitOrders']);
    Route::get('spot-settings', [SpotSettingController::class, 'show']);
    Route::put('spot-settings', [SpotSettingController::class, 'update']);

    Route::get('stakes', [IssueController::class, 'index']);
    Route::get('stakes/form-meta', [IssueController::class, 'formMeta']);
    Route::post('stakes/upload-image', [IssueController::class, 'uploadImage']);
    Route::get('stakes/{id}', [IssueController::class, 'show'])->whereNumber('id');
    Route::post('stakes', [IssueController::class, 'store']);
    Route::put('stakes/{id}', [IssueController::class, 'update'])->whereNumber('id');
    Route::get('staking-logs', [IssueController::class, 'logs']);

    Route::get('miners', [MinerController::class, 'index']);
    Route::get('miners/form-meta', [MinerController::class, 'formMeta']);
    Route::post('miners/upload-image', [MinerController::class, 'uploadImage']);
    Route::get('miners/{id}', [MinerController::class, 'show'])->whereNumber('id');
    Route::post('miners', [MinerController::class, 'store']);
    Route::put('miners/{id}', [MinerController::class, 'update'])->whereNumber('id');
    Route::put('miners/status', [MinerController::class, 'updateStatus']);

    Route::get('miner-orders', [MinerController::class, 'orders']);
    Route::get('miner-orders/expired', [MinerController::class, 'expiredOrders']);
    Route::put('miner-orders/status', [MinerController::class, 'updateOrderStatus']);

    Route::get('miner-profits', [MinerController::class, 'profits']);
    Route::get('frozen-profits', [MinerController::class, 'frozenProfits']);

    Route::get('mining-pools', [MiningPoolController::class, 'index']);
    Route::get('mining-pools/form-meta', [MiningPoolController::class, 'formMeta']);
    Route::get('mining-pools/{id}', [MiningPoolController::class, 'show'])->whereNumber('id');
    Route::post('mining-pools', [MiningPoolController::class, 'store']);
    Route::put('mining-pools/{id}', [MiningPoolController::class, 'update'])->whereNumber('id');
    Route::delete('mining-pools/{id}', [MiningPoolController::class, 'destroy'])->whereNumber('id');

    Route::get('online-support', [OnlineSupportController::class, 'index']);
    Route::get('online-support/users/{userId}/messages', [OnlineSupportController::class, 'userMessages'])
        ->whereNumber('userId');
    Route::get('online-support/messages/{id}', [OnlineSupportController::class, 'showMessage'])
        ->whereNumber('id');
    Route::post('online-support/messages/{id}/reply', [OnlineSupportController::class, 'reply'])
        ->whereNumber('id');

    Route::get('site-config', [SiteConfigController::class, 'show']);
    Route::put('site-config', [SiteConfigController::class, 'update']);
    Route::post('site-config/upload-image', [SiteConfigController::class, 'uploadPublicImage']);
    Route::post('site-config/upload-editor-image', [SiteConfigController::class, 'uploadEditorImage']);

    Route::get('system-params', [SiteConfigController::class, 'showSystemParams']);
    Route::put('system-params', [SiteConfigController::class, 'updateSystemParams']);

    Route::get('coins', [CoinController::class, 'index']);
    Route::post('coins/upload-image', [CoinController::class, 'uploadImage']);
    Route::get('coins/{id}', [CoinController::class, 'show'])->whereNumber('id');
    Route::post('coins', [CoinController::class, 'store']);
    Route::put('coins/{id}', [CoinController::class, 'update'])->whereNumber('id');
    Route::put('coins/status', [CoinController::class, 'updateStatus']);

    Route::get('deposit-ports', [DepositPortController::class, 'index']);
    Route::get('deposit-ports/form-meta', [DepositPortController::class, 'formMeta']);
    Route::get('deposit-ports/{id}', [DepositPortController::class, 'show'])->whereNumber('id');
    Route::post('deposit-ports', [DepositPortController::class, 'store']);
    Route::put('deposit-ports/{id}', [DepositPortController::class, 'update'])->whereNumber('id');
    Route::put('deposit-ports/status', [DepositPortController::class, 'updateStatus']);

    Route::get('frontend-navigation', [FrontendNavigationController::class, 'index']);
    Route::get('frontend-navigation/form-meta', [FrontendNavigationController::class, 'formMeta']);
    Route::get('frontend-navigation/{id}', [FrontendNavigationController::class, 'show'])->whereNumber('id');
    Route::post('frontend-navigation', [FrontendNavigationController::class, 'store']);
    Route::put('frontend-navigation/{id}', [FrontendNavigationController::class, 'update'])->whereNumber('id');
    Route::put('frontend-navigation/status', [FrontendNavigationController::class, 'updateStatus']);

    Route::get('footer-navigation', [FooterNavigationController::class, 'index']);
    Route::get('footer-navigation/form-meta', [FooterNavigationController::class, 'formMeta']);
    Route::get('footer-navigation/{id}', [FooterNavigationController::class, 'show'])->whereNumber('id');
    Route::post('footer-navigation', [FooterNavigationController::class, 'store']);
    Route::put('footer-navigation/{id}', [FooterNavigationController::class, 'update'])->whereNumber('id');
    Route::put('footer-navigation/status', [FooterNavigationController::class, 'updateStatus']);

    Route::get('ct-markets', [CtMarketController::class, 'index']);
    Route::get('ct-markets/{id}', [CtMarketController::class, 'show'])->whereNumber('id');
    Route::post('ct-markets', [CtMarketController::class, 'store']);
    Route::put('ct-markets/{id}', [CtMarketController::class, 'update'])->whereNumber('id');
    Route::put('ct-markets/status', [CtMarketController::class, 'updateStatus']);

    Route::get('platform-markets', [PlatformMarketController::class, 'index']);
    Route::get('platform-markets/form-meta', [PlatformMarketController::class, 'formMeta']);
    Route::get('platform-markets/{id}', [PlatformMarketController::class, 'show'])->whereNumber('id');
    Route::post('platform-markets', [PlatformMarketController::class, 'store']);
    Route::put('platform-markets/{id}', [PlatformMarketController::class, 'update'])->whereNumber('id');
    Route::put('platform-markets/{id}/quotes', [PlatformMarketController::class, 'updateQuotes'])->whereNumber('id');
    Route::put('platform-markets/{id}/robot', [PlatformMarketController::class, 'updateRobot'])->whereNumber('id');
    Route::put('platform-markets/status', [PlatformMarketController::class, 'updateStatus']);
});
