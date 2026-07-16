<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BillResource;
use App\Http\Resources\Admin\UserLogResource;
use App\Models\Bill;
use App\Models\Hyorder;
use App\Models\IssueLog;
use App\Models\Kjorder;
use App\Models\Myzc;
use App\Models\Recharge;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $today = now()->toDateString();
        $onlineThreshold = now()->subMinutes(30)->timestamp;

        $totalMembers = User::query()->count();
        $onlineMembers = User::query()
            ->where(function ($query) use ($today, $onlineThreshold) {
                $query->where('logintime', '>=', $onlineThreshold)
                    ->orWhereDate('lgtime', $today);
            })
            ->count();
        $totalAgents = User::query()->where('is_agent', 1)->count();

        $todayDeposit = round(
            (float) Recharge::query()
                ->where('status', 2)
                ->whereDate('addtime', $today)
                ->sum('num'),
            4,
        );
        $todayWithdrawal = round(
            (float) Myzc::query()
                ->where('status', 2)
                ->whereDate('addtime', $today)
                ->sum('num'),
            4,
        );
        $systemBalance = round(
            (float) DB::table('tw_bborder')->where('status', 2)->sum('usdtnum'),
            4,
        );

        $revenueTrend = $this->dailyFinancialTrend(14);
        $depositWithdrawalTrend = $this->depositWithdrawalTrend(7);

        $topAgents = $this->topAgents(5);
        $latestMembers = $this->latestMembers(5);

        $recentLogins = UserLog::query()->orderByDesc('id')->limit(6)->get();
        $this->attachUsernamesToLogs($recentLogins);

        $recentTransactions = Bill::query()->orderByDesc('id')->limit(6)->get();

        return response()->json([
            'status' => true,
            'data' => [
                'alluser' => $totalMembers,
                'allhy' => Hyorder::query()->where('status', 1)->count(),
                'bball' => $systemBalance,
                'allkj' => Kjorder::query()->where('status', 1)->count(),
                'allissue' => IssueLog::query()->where('status', 1)->count(),
                'allcz' => round((float) Recharge::query()->where('status', 2)->sum('num'), 4),
                'alltx' => round((float) Myzc::query()->where('status', 2)->sum('num'), 4),
                'allline' => User::query()->whereDate('lgtime', $today)->count(),
                'kpis' => [
                    'total_members' => $totalMembers,
                    'online_members' => $onlineMembers,
                    'total_agents' => $totalAgents,
                    'today_deposit' => $todayDeposit,
                    'today_withdrawal' => $todayWithdrawal,
                    'system_balance' => $systemBalance,
                ],
                'revenue_trend' => $revenueTrend,
                'deposit_withdrawal_trend' => $depositWithdrawalTrend,
                'top_agents' => $topAgents,
                'latest_members' => $latestMembers,
                'recent_logins' => UserLogResource::collection($recentLogins),
                'recent_transactions' => BillResource::collection($recentTransactions),
            ],
        ]);
    }

    /**
     * Pending action counts for sidebar badges (ThinkPHP Trade/getRecharges, getWithdraw, gethyorder).
     */
    public function pendingCounts(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => [
                'deposits' => Recharge::query()->where('status', 1)->count(),
                'withdrawals' => Myzc::query()->where('status', 1)->count(),
                'contract_orders' => Hyorder::query()->where('status', 1)->count(),
            ],
        ]);
    }

    /**
     * @return list<array{date: string, amount: float}>
     */
    private function dailyFinancialTrend(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $rows = Recharge::query()
            ->where('status', 2)
            ->where('addtime', '>=', $start)
            ->selectRaw('DATE(addtime) as day, SUM(num) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $trend = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $trend[] = [
                'date' => $date,
                'amount' => round((float) ($rows[$date] ?? 0), 4),
            ];
        }

        return $trend;
    }

    /**
     * @return list<array{date: string, deposit: float, withdrawal: float}>
     */
    private function depositWithdrawalTrend(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $deposits = Recharge::query()
            ->where('status', 2)
            ->where('addtime', '>=', $start)
            ->selectRaw('DATE(addtime) as day, SUM(num) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $withdrawals = Myzc::query()
            ->where('status', 2)
            ->where('addtime', '>=', $start)
            ->selectRaw('DATE(addtime) as day, SUM(num) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $trend = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $trend[] = [
                'date' => $date,
                'deposit' => round((float) ($deposits[$date] ?? 0), 4),
                'withdrawal' => round((float) ($withdrawals[$date] ?? 0), 4),
            ];
        }

        return $trend;
    }

    /**
     * @return list<array{id: int, username: string, referrals: int, invit: string|null}>
     */
    private function topAgents(int $limit): array
    {
        $agents = User::query()
            ->where('is_agent', 1)
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        if ($agents->isEmpty()) {
            return [];
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

        return $agents
            ->map(function (User $agent) use ($oneCounts, $twoCounts, $threeCounts) {
                $referrals = (int) ($oneCounts[$agent->id] ?? 0)
                    + (int) ($twoCounts[$agent->id] ?? 0)
                    + (int) ($threeCounts[$agent->id] ?? 0);

                return [
                    'id' => $agent->id,
                    'username' => $agent->username,
                    'invit' => $agent->invit,
                    'referrals' => $referrals,
                ];
            })
            ->sortByDesc('referrals')
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @return list<array{id: int, username: string, addtime: int, status: int, is_agent: int}>
     */
    private function latestMembers(int $limit): array
    {
        return User::query()
            ->select(['id', 'username', 'addtime', 'status', 'is_agent'])
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'username' => $user->username,
                'addtime' => (int) $user->addtime,
                'status' => (int) $user->status,
                'is_agent' => (int) $user->is_agent,
            ])
            ->all();
    }

    /**
     * @param Collection<int, UserLog> $logs
     */
    private function attachUsernamesToLogs(Collection $logs): void
    {
        if ($logs->isEmpty()) {
            return;
        }

        $userIds = $logs->pluck('userid')->unique()->filter()->all();
        $usernames = User::query()->whereIn('id', $userIds)->pluck('username', 'id');

        foreach ($logs as $log) {
            $log->username = $usernames[$log->userid] ?? null;
        }
    }
}
