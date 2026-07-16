"use client";
import { useMemo, useState } from "react";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { ActionButton, ToolbarActions, useRowSelection } from "@/components/actions";
import { DataTableCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, RowCheckbox, SelectCell, UsernameFilter, actionsColumn } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { formatCompactTimestamp } from "@/features/trading/lib/format";
import { ActiveMinerListSkeleton } from "./ActiveMinerListSkeleton";
import { updateMinerOrderStatus } from "./useMinerOrderActions";
import { useMinerRows } from "./useMiners";

export function ActiveMinerListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [pendingBulk, setPendingBulk] = useState<1 | 2 | 3 | null>(null);
  const queryClient = useQueryClient();
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useMinerRows(queryParams);
  const bulkAction = useMutation({ mutationFn: ({ ids, type }: { ids: number[]; type: 1 | 2 | 3 }) => updateMinerOrderStatus(ids, type), onSuccess: () => queryClient.invalidateQueries({ queryKey: ["admin", "miner-orders"] }) });
  const items = data?.data ?? [];
  const meta = data?.meta;
  const selection = useRowSelection(items);
  const columns = [
    { key: "username", label: t("common.username") },
    { key: "detail", label: "Detail" },
    { key: "amount", label: t("common.amount") },
    { key: "addtime", label: t("common.time") },
  ];

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.minerActive.title" descriptionKey="page.minerActive.description" action={
        <ToolbarActions>
          <ActionButton variant="success" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk(1)}>{t("action.enableIncome")}</ActionButton>
          <ActionButton variant="warning" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk(2)}>{t("action.pauseIncome")}</ActionButton>
          <ActionButton variant="danger" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk(3)}>{t("action.deleteMiner")}</ActionButton>
        </ToolbarActions>
      } />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <ActiveMinerListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.minerActive.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns} selectable allSelected={selection.allSelected} someSelected={selection.someSelected} onToggleAll={selection.toggleAll}>
            {items.map((item) => (
              <tr key={item.id}>
                <SelectCell><RowCheckbox checked={selection.isSelected(item.id)} onChange={() => selection.toggleOne(item.id)} /></SelectCell>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="detail">{item.kjtitle ?? item.ktitle ?? item.status_label ?? "—"}</DataTableCell>
                <DataTableCell columnKey="amount" className="tabular-nums">
                  {item.num != null ? formatAmount(item.num) : "—"} {item.coin ?? ""}
                </DataTableCell>
                <DataTableCell columnKey="addtime">{formatCompactTimestamp(item.addtime)}</DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      <ConfirmDialog isOpen={pendingBulk !== null} title={t("common.confirm")} message={pendingBulk ? `Apply action to ${selection.selectedIds.length} miner order(s)?` : ""} confirmLabel={t("common.confirm")} variant={pendingBulk === 3 ? "danger" : "default"} isPending={bulkAction.isPending} onConfirm={async () => { if (pendingBulk) { await bulkAction.mutateAsync({ ids: selection.selectedIds, type: pendingBulk }); selection.clearSelection(); setPendingBulk(null); } }} onCancel={() => { if (!bulkAction.isPending) setPendingBulk(null); }} />
    </div>
  );
}
