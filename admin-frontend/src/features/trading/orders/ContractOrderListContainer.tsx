"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
} from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { contractKongykLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import Link from "next/link";
import { Suspense, useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { ContractOrderList } from "./ContractOrderList";
import { ContractOrderListSkeleton } from "./ContractOrderListSkeleton";
import { useContractOrderActions } from "./useContractOrderActions";
import { useContractOrders } from "./useContractOrders";
import type { ContractOrder } from "./types";
import { ContractQueuePanelContainer } from "@/features/trading/queue/ContractQueuePanelContainer";
import { ContractQueueQuickActions } from "@/features/trading/queue/ContractQueueQuickActions";
import { contractOpsButtonClass } from "@/features/trading/queue/contract-ops-styles";

type PendingAction = {
  order: ContractOrder;
  kongyk: 0 | 1 | 2;
};

type ContractOrderListContainerProps = {
  embedded?: boolean;
};

type ContractOrderListViewProps = {
  embedded: boolean;
  page: number;
  perPage: number;
  onPageChange?: (page: number) => void;
};

function ContractOrderListView({
  embedded,
  page,
  perPage,
  onPageChange,
}: ContractOrderListViewProps) {
  const { t } = useI18n();

  const [pendingAction, setPendingAction] = useState<PendingAction | null>(null);
  const [pendingSettle, setPendingSettle] = useState<ContractOrder | null>(null);
  const [pendingSettleStuck, setPendingSettleStuck] = useState(false);
  const [actionError, setActionError] = useState<string | null>(null);
  const [actionSuccess, setActionSuccess] = useState<string | null>(null);

  const queryParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: perPage,
    }),
    [page, perPage],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = useContractOrders(queryParams);
  const { setWinLoss, manualSettle, settleStuck } = useContractOrderActions();

  const handleConfirm = async () => {
    if (!pendingAction) return;

    setActionError(null);
    const { order, kongyk } = pendingAction;

    try {
      await setWinLoss.mutateAsync({ id: order.id, kongyk });
      setPendingAction(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const handleSettleConfirm = async () => {
    if (!pendingSettle) return;

    setActionError(null);

    try {
      await manualSettle.mutateAsync(pendingSettle.id);
      setPendingSettle(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const handleSettleStuckConfirm = async () => {
    setActionError(null);
    setActionSuccess(null);

    try {
      const res = await settleStuck.mutateAsync();
      setPendingSettleStuck(false);
      const settled = res.data?.settled ?? 0;
      setActionSuccess(
        settled > 0
          ? t("page.orders.settleStuckSuccess", { count: String(settled) })
          : t("page.orders.settleStuckNone"),
      );
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const orders = data?.data ?? [];
  const meta = data?.meta;
  const isWinLossPending = setWinLoss.isPending;
  const isSettlePending = manualSettle.isPending;
  const pendingActionId =
    isWinLossPending && pendingAction
      ? pendingAction.order.id
      : isSettlePending && pendingSettle
        ? pendingSettle.id
        : null;

  const pendingControlLabel = pendingAction
    ? contractKongykLabel(t, pendingAction.kongyk)
    : "";

  const settleStuckButton = (
    <button
      type="button"
      disabled={settleStuck.isPending}
      onClick={() => {
        setActionError(null);
        setActionSuccess(null);
        setPendingSettleStuck(true);
      }}
      className={`${contractOpsButtonClass} bg-primary text-[var(--color-on-primary)]`}
    >
      {t("action.settleStuckOrders")}
    </button>
  );

  const confirmDialogs = (
    <>
      <ConfirmDialog
        isOpen={pendingAction !== null}
        title={t("page.orders.setControlTitle")}
        message={
          pendingAction
            ? t("page.orders.setControlMessage", {
                id: String(pendingAction.order.id),
                username: pendingAction.order.username,
                control: pendingControlLabel,
              })
            : ""
        }
        confirmLabel={t("common.confirm")}
        variant={pendingAction?.kongyk === 2 ? "danger" : "default"}
        isPending={isWinLossPending}
        onConfirm={handleConfirm}
        onCancel={() => {
          if (!isWinLossPending) {
            setPendingAction(null);
          }
        }}
      />
      <ConfirmDialog
        isOpen={pendingSettle !== null}
        title={t("page.orders.settleTitle")}
        message={
          pendingSettle
            ? t("page.orders.settleMessage", {
                id: String(pendingSettle.id),
                username: pendingSettle.username,
              })
            : ""
        }
        confirmLabel={t("action.settleOrder")}
        isPending={isSettlePending}
        onConfirm={handleSettleConfirm}
        onCancel={() => {
          if (!isSettlePending) {
            setPendingSettle(null);
          }
        }}
      />
      <ConfirmDialog
        isOpen={pendingSettleStuck}
        title={t("page.orders.settleStuckTitle")}
        message={t("page.orders.settleStuckMessage")}
        confirmLabel={t("action.settleStuckOrders")}
        isPending={settleStuck.isPending}
        onConfirm={handleSettleStuckConfirm}
        onCancel={() => {
          if (!settleStuck.isPending) setPendingSettleStuck(false);
        }}
      />
    </>
  );

  const listContent = (
    <>
      {actionSuccess ? (
        <div role="status" className="rounded-lg border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
          {actionSuccess}
        </div>
      ) : null}
      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <ContractOrderListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("common.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && orders.length === 0 ? (
        <EmptyState titleKey="page.orders.noResults" descriptionKey="common.noResultsHint" />
      ) : null}

      {!isLoading && !isError && orders.length > 0 ? (
        <>
          {!embedded && meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <ContractOrderList
            orders={orders}
            pendingActionId={pendingActionId}
            embedded={embedded}
            onSetWinLoss={(order, kongyk) => {
              setActionError(null);
              setPendingAction({ order, kongyk });
            }}
            onSettle={(order) => {
              setActionError(null);
              setPendingSettle(order);
            }}
          />
          {!embedded && meta && onPageChange ? (
            <PaginationNav
              meta={meta}
              onPageChange={onPageChange}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}
    </>
  );

  if (embedded) {
    return (
      <section aria-label={t("page.dashboard.contractOrders")} className="space-y-4">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
          <div className="min-w-0">
            <h2 className="text-base font-semibold text-foreground">{t("page.dashboard.contractOrders")}</h2>
            <p className="mt-0.5 text-sm text-muted">{t("page.dashboard.contractOrdersHint")}</p>
          </div>
          <div className="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end">
            <ContractQueueQuickActions />
            {settleStuckButton}
            <Link
              href="/trading/orders"
              className="text-sm font-medium text-primary transition hover:opacity-80"
            >
              {t("page.dashboard.viewAll")}
            </Link>
          </div>
        </div>
        {listContent}
        {confirmDialogs}
      </section>
    );
  }

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey="page.orders.title"
        descriptionKey="page.orders.description"
        action={settleStuckButton}
      />

      {listContent}

      <section
        aria-labelledby="result-queue-heading"
        className="space-y-4 rounded-xl border border-border bg-surface p-4 md:p-6"
      >
        <ContractQueuePanelContainer embedded part="actions" />
        <ContractQueuePanelContainer embedded part="table" />
      </section>

      {confirmDialogs}
    </div>
  );
}

function ContractOrderListPaginated() {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  const page = Number(searchParams.get("page") ?? "1");

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") {
          next.delete(key);
        } else {
          next.set(key, value);
        }
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  return (
    <ContractOrderListView
      embedded={false}
      page={page}
      perPage={15}
      onPageChange={(p) => updateParams({ page: String(p) })}
    />
  );
}

export function ContractOrderListContainer({ embedded = false }: ContractOrderListContainerProps) {
  if (embedded) {
    return <ContractOrderListView embedded page={1} perPage={10} />;
  }

  return (
    <Suspense fallback={<ContractOrderListSkeleton />}>
      <ContractOrderListPaginated />
    </Suspense>
  );
}
