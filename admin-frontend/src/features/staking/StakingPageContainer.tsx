"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
} from "@/components/list/ListPageParts";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { StakeFormDialog } from "./StakeFormDialog";
import { StakeList } from "./StakeList";
import { StakingLogList } from "./StakingLogList";
import { StakeListSkeleton, StakingLogListSkeleton } from "./StakingSkeletons";
import { useStake, useStakeFormMeta, useStakes, useStakingLogs } from "./useStaking";
import { useStakeActions } from "./useStakeActions";
import type { AdminStake, StakeUpsertPayload } from "./types";

type Tab = "packages" | "logs";

export function StakingPageContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const tab = (searchParams.get("tab") === "logs" ? "logs" : "packages") as Tab;
  const page = Number(searchParams.get("page") ?? "1");
  const account = searchParams.get("account") ?? "";

  const [accountInput, setAccountInput] = useState(account);
  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formError, setFormError] = useState<string | null>(null);
  const [pendingActionId, setPendingActionId] = useState<number | null>(null);

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

  const stakesParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 15,
    }),
    [page],
  );

  const logsParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 15,
      account: account || undefined,
    }),
    [page, account],
  );

  const stakesQuery = useStakes(stakesParams);
  const logsQuery = useStakingLogs(logsParams);
  const { data: detailData, isLoading: isLoadingDetail } = useStake(formOpen ? editingId : null);
  useStakeFormMeta(formOpen);
  const { create, update } = useStakeActions();

  const activeQuery = tab === "packages" ? stakesQuery : logsQuery;
  const { isLoading, isError, error, refetch, isFetching } = activeQuery;

  const stakes = stakesQuery.data?.data ?? [];
  const logs = logsQuery.data?.data ?? [];
  const meta = activeQuery.data?.meta;
  const editingItem = editingId ? (detailData?.data ?? null) : null;
  const isFormPending = create.isPending || update.isPending;

  const handleTabChange = (nextTab: Tab) => {
    updateParams({ tab: nextTab === "packages" ? null : nextTab, page: "1" });
  };

  const handleLogSearch = (e: React.FormEvent) => {
    e.preventDefault();
    updateParams({ account: accountInput.trim() || null, page: "1" });
  };

  const handleOpenCreate = () => {
    setFormError(null);
    setEditingId(null);
    setFormOpen(true);
  };

  const handleOpenEdit = (stake: AdminStake) => {
    setFormError(null);
    setEditingId(stake.id);
    setFormOpen(true);
  };

  const handleFormSubmit = async (payload: StakeUpsertPayload) => {
    setFormError(null);
    setPendingActionId(editingId);
    try {
      if (editingId) {
        await update.mutateAsync({ id: editingId, payload });
      } else {
        await create.mutateAsync(payload);
      }
      setFormOpen(false);
      setEditingId(null);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
    } finally {
      setPendingActionId(null);
    }
  };

  const headerAction =
    tab === "packages" ? (
      <button
        type="button"
        onClick={handleOpenCreate}
        className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
      >
        {t("action.add")}
      </button>
    ) : undefined;

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.staking.title" descriptionKey="page.staking.description" action={headerAction} />

      <div
        role="tablist"
        aria-label={t("page.staking.tabsLabel")}
        className="flex gap-1 rounded-lg border border-border bg-surface p-1"
      >
        <button
          type="button"
          role="tab"
          aria-selected={tab === "packages"}
          onClick={() => handleTabChange("packages")}
          className={`flex-1 rounded px-4 py-2 text-sm font-medium transition ${
            tab === "packages"
              ? "bg-surface-elevated text-primary"
              : "text-muted hover:text-foreground"
          }`}
        >
          {t("page.staking.tab.packages")}
        </button>
        <button
          type="button"
          role="tab"
          aria-selected={tab === "logs"}
          onClick={() => handleTabChange("logs")}
          className={`flex-1 rounded px-4 py-2 text-sm font-medium transition ${
            tab === "logs"
              ? "bg-surface-elevated text-primary"
              : "text-muted hover:text-foreground"
          }`}
        >
          {t("page.staking.tab.logs")}
        </button>
      </div>

      {tab === "logs" ? (
        <form
          onSubmit={handleLogSearch}
          className="flex flex-col gap-3 rounded-lg border border-border bg-surface p-4 sm:flex-row sm:items-end"
          role="search"
          aria-label={t("page.staking.logSearchLabel")}
        >
          <div className="flex-1">
            <label htmlFor="log-account" className="block text-sm font-medium text-foreground">
              {t("common.account")}
            </label>
            <input
              id="log-account"
              type="search"
              value={accountInput}
              onChange={(e) => setAccountInput(e.target.value)}
              placeholder={t("page.staking.logSearchPlaceholder")}
              className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground placeholder:text-muted"
            />
          </div>
          <button
            type="submit"
            className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
          >
            {t("common.search")}
          </button>
        </form>
      ) : null}

      {isLoading ? (
        tab === "packages" ? <StakeListSkeleton /> : <StakingLogListSkeleton />
      ) : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.staking.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && tab === "packages" && stakes.length === 0 ? (
        <EmptyState
          titleKey="page.staking.noPackages"
          descriptionKey="page.staking.noPackagesHint"
          action={
            <button
              type="button"
              onClick={handleOpenCreate}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
            >
              {t("action.add")}
            </button>
          }
        />
      ) : null}

      {!isLoading && !isError && tab === "logs" && logs.length === 0 ? (
        <EmptyState
          titleKey="page.staking.noLogs"
          descriptionKey="page.staking.noLogsHint"
        />
      ) : null}

      {!isLoading && !isError && tab === "packages" && stakes.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <StakeList stakes={stakes} pendingId={pendingActionId} onEdit={handleOpenEdit} />
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}

      {!isLoading && !isError && tab === "logs" && logs.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <StakingLogList logs={logs} />
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}

      <StakeFormDialog
        isOpen={formOpen}
        editingItem={editingItem}
        isLoadingDetail={Boolean(editingId && isLoadingDetail)}
        isPending={isFormPending}
        error={formError}
        onSubmit={handleFormSubmit}
        onClose={() => {
          if (!isFormPending) {
            setFormOpen(false);
            setEditingId(null);
            setFormError(null);
          }
        }}
      />
    </div>
  );
}
