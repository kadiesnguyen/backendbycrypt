"use client";
import { useMemo, useState } from "react";
import { ActionButton, RowActions } from "@/components/actions";
import { DataTableCell, ActionsCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, UsernameFilter, actionsColumn } from "@/components/list/ListPageParts"
import { ErrorState } from "@/components/ui/ErrorState";
import { contractStatusLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { TrialOrderListSkeleton } from "./TrialOrderListSkeleton";
import { useTrialOrders } from "./useTrialOrders";

export function TrialOrderListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [viewItem, setViewItem] = useState<Record<string, unknown> | null>(null);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useTrialOrders(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = [
    { key: "username", label: t("common.username") }, { key: "coinname", label: t("common.coin") }, { key: "num", label: t("common.amount") },
    { key: "status_label", label: t("common.status") }, { key: "buytime", label: t("common.time") },
    actionsColumn(t),
  ];
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.trialOrders.title" descriptionKey="page.trialOrders.description" />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <TrialOrderListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.trialOrders.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="coinname">{item.coinname?.toUpperCase()}</DataTableCell>
                <DataTableCell columnKey="num" className="tabular-nums">{item.num}</DataTableCell>
                <DataTableCell columnKey="status_label">{contractStatusLabel(t, item.status)}</DataTableCell>
                <DataTableCell columnKey="buytime">{item.buytime ?? item.addtime ?? "—"}</DataTableCell>
                <ActionsCell><RowActions><ActionButton onClick={() => setViewItem(item as unknown as Record<string, unknown>)}>{t("action.check")}</ActionButton></RowActions></ActionsCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      {viewItem ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <div className="max-h-[80vh] w-full max-w-lg overflow-auto rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{t("action.check")} #{String(viewItem.id)}</h2>
            <pre className="mt-4 whitespace-pre-wrap text-xs text-muted">{JSON.stringify(viewItem, null, 2)}</pre>
            <div className="mt-4 flex justify-end"><ActionButton variant="ghost" onClick={() => setViewItem(null)}>{t("common.cancel")}</ActionButton></div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
