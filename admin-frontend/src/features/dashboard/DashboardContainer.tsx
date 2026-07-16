"use client";

import { Suspense } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { ContractOrderListContainer } from "@/features/trading/orders/ContractOrderListContainer";
import { ContractOrderListSkeleton } from "@/features/trading/orders/ContractOrderListSkeleton";
import { DepositWithdrawalChart } from "./components/DepositWithdrawalChart";
import { DashboardCard } from "./components/DashboardCard";
import { KpiCard } from "./components/KpiCard";
import { LatestMembersPanel } from "./components/LatestMembersPanel";
import { RecentLoginTable } from "./components/RecentLoginTable";
import { RecentTransactionsTable } from "./components/RecentTransactionsTable";
import { RevenueTrendChart } from "./components/RevenueTrendChart";
import { TopAgentsPanel } from "./components/TopAgentsPanel";
import { DashboardSkeleton } from "./DashboardSkeleton";
import { formatCurrency, formatNumber } from "./format";
import { useDashboard } from "./useDashboard";

export function DashboardContainer() {
  const { t } = useI18n();
  const { data, isLoading, isError, error, refetch, isFetching } = useDashboard();

  if (isLoading) {
    return <DashboardSkeleton />;
  }

  if (isError || !data) {
    return (
      <ErrorState
        message={error instanceof Error ? error.message : t("common.loadFailed")}
        retry={() => refetch()}
      />
    );
  }

  const { kpis } = data;

  return (
    <div className="flex min-w-0 flex-col gap-4 overflow-x-hidden md:gap-6">
      <header className="flex min-w-0 items-start justify-between gap-4">
        <div className="min-w-0">
          <h1 className="text-base font-semibold text-foreground">{t("page.dashboard.title")}</h1>
          <p className="mt-0.5 text-sm text-muted">{t("page.dashboard.description")}</p>
        </div>
        {isFetching ? (
          <span className="shrink-0 rounded-lg border border-border bg-surface px-2.5 py-1 text-xs text-primary">
            {t("common.updating")}
          </span>
        ) : null}
      </header>

      {/* Contract orders — ops quick panel */}
      <section aria-label={t("page.dashboard.contractOrders")} className="min-w-0">
        <div className="dashboard-card p-4 md:p-5">
          <Suspense fallback={<ContractOrderListSkeleton />}>
            <ContractOrderListContainer embedded />
          </Suspense>
        </div>
      </section>

      {/* KPI metrics — 12-col grid (DESIGN.md dashboard) */}
      <section aria-label={t("page.dashboard.kpiSection")} className="grid grid-cols-12 gap-4 md:gap-6">
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.totalMembers")}
            value={formatNumber(kpis.total_members)}
            accent="gold"
          />
        </div>
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.onlineMembers")}
            value={formatNumber(kpis.online_members)}
            sublabel={t("page.dashboard.kpi.onlineHint")}
            accent="green"
          />
        </div>
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.totalAgents")}
            value={formatNumber(kpis.total_agents)}
            accent="blue"
          />
        </div>
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.todayDeposit")}
            value={formatCurrency(kpis.today_deposit)}
            accent="green"
          />
        </div>
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.todayWithdrawal")}
            value={formatCurrency(kpis.today_withdrawal)}
            accent="red"
          />
        </div>
        <div className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
          <KpiCard
            label={t("page.dashboard.kpi.systemBalance")}
            value={formatCurrency(kpis.system_balance)}
            accent="gold"
          />
        </div>
      </section>

      {/* Charts */}
      <section
        aria-label={t("page.dashboard.chartsSection")}
        className="grid grid-cols-12 items-stretch gap-4 md:gap-6"
      >
        <div className="col-span-12 min-w-0 lg:col-span-8">
          <DashboardCard
            title={t("page.dashboard.revenueTrend")}
            bodyClassName="h-[220px] px-3 py-2"
            className="h-full min-h-[260px]"
          >
            <RevenueTrendChart data={data.revenue_trend} />
          </DashboardCard>
        </div>
        <div className="col-span-12 min-w-0 lg:col-span-4">
          <DashboardCard
            title={t("page.dashboard.depositVsWithdrawal")}
            bodyClassName="h-[220px] px-3 py-2"
            className="h-full min-h-[260px]"
          >
            <DepositWithdrawalChart data={data.deposit_withdrawal_trend} />
          </DashboardCard>
        </div>
      </section>

      {/* Rankings */}
      <section
        aria-label={t("page.dashboard.activitySection")}
        className="grid grid-cols-12 items-stretch gap-4 md:gap-6"
      >
        <div className="col-span-12 min-w-0 lg:col-span-6">
          <TopAgentsPanel
            title={t("page.dashboard.topAgents")}
            viewAllLabel={t("page.dashboard.viewAll")}
            agents={data.top_agents}
            emptyLabel={t("page.dashboard.noAgents")}
          />
        </div>
        <div className="col-span-12 min-w-0 lg:col-span-6">
          <LatestMembersPanel
            title={t("page.dashboard.latestMembers")}
            viewAllLabel={t("page.dashboard.viewAll")}
            members={data.latest_members}
            emptyLabel={t("page.dashboard.noMembers")}
            statusActive={t("page.dashboard.statusActive")}
            statusInactive={t("page.dashboard.statusInactive")}
            agentBadge={t("page.dashboard.agentBadge")}
          />
        </div>
      </section>

      {/* Activity tables */}
      <section
        aria-label={t("page.dashboard.tablesSection")}
        className="grid grid-cols-12 items-stretch gap-4 md:gap-6"
      >
        <div className="col-span-12 min-w-0 2xl:col-span-6">
          <RecentTransactionsTable
            title={t("page.dashboard.recentTransactions")}
            viewAllLabel={t("page.dashboard.viewAll")}
            transactions={data.recent_transactions}
            emptyLabel={t("page.dashboard.noTransactions")}
            columns={{
              user: t("common.username"),
              amount: t("page.dashboard.col.amount"),
              coin: t("page.dashboard.col.coin"),
              type: t("page.dashboard.col.type"),
              time: t("page.dashboard.col.time"),
            }}
          />
        </div>
        <div className="col-span-12 min-w-0 2xl:col-span-6">
          <RecentLoginTable
            title={t("page.dashboard.recentLogins")}
            viewAllLabel={t("page.dashboard.viewAll")}
            logins={data.recent_logins}
            emptyLabel={t("page.dashboard.noLogins")}
            columns={{
              user: t("common.username"),
              ip: t("page.dashboard.col.ip"),
              location: t("page.dashboard.col.location"),
              time: t("page.dashboard.col.time"),
              status: t("common.status"),
            }}
          />
        </div>
      </section>
    </div>
  );
}
