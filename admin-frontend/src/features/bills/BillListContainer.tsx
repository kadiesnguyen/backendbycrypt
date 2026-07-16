"use client";

import { useMemo, useState } from "react";
import { ActionButton, ToolbarActions, useRowSelection } from "@/components/actions";
import { DataTableCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, RowCheckbox, SelectCell, UsernameFilter } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { BillListSkeleton } from "./BillListSkeleton";
import { useBillActions } from "./useBillActions";
import { useBills } from "./useBills";

type BillListContainerProps = {
  titleKey?: string;
  descriptionKey?: string;
  noResultsKey?: string;
};

export function BillListContainer({
  titleKey = "page.bills.title",
  descriptionKey = "page.bills.description",
  noResultsKey = "page.bills.noResults",
}: BillListContainerProps) {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [pendingDelete, setPendingDelete] = useState(false);
  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }),
    [page, username],
  );
  const { data, isLoading, isError, error, refetch, isFetching } = useBills(queryParams);
  const { remove } = useBillActions();
  const items = data?.data ?? [];
  const meta = data?.meta;
  const selection = useRowSelection(items);

  const columns = [
    { key: "username", label: t("common.username") },
    { key: "coinname", label: t("common.coin") },
    { key: "num", label: t("common.amount") },
    { key: "st_label", label: t("common.type") },
    { key: "remark", label: t("common.remark") },
    { key: "addtime", label: t("common.time") },
  ];

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey={titleKey}
        descriptionKey={descriptionKey}
        action={
          <ToolbarActions>
            <ActionButton variant="ghost" onClick={() => { setUsernameInput(""); updateParams({ username: null, page: "1" }); }}>{t("action.resetSearch")}</ActionButton>
            <ActionButton variant="danger" disabled={selection.selectedIds.length === 0} onClick={() => setPendingDelete(true)}>{t("common.delete")}</ActionButton>
          </ToolbarActions>
        }
      />
      <UsernameFilter
        value={usernameInput}
        onChange={setUsernameInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ username: usernameInput.trim() || null, page: "1" });
        }}
      />
      {isLoading ? <BillListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey={noResultsKey} descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns} selectable allSelected={selection.allSelected} someSelected={selection.someSelected} onToggleAll={selection.toggleAll}>
            {items.map((item) => (
              <tr key={item.id}>
                <SelectCell><RowCheckbox checked={selection.isSelected(item.id)} onChange={() => selection.toggleOne(item.id)} /></SelectCell>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="coinname">{item.coinname?.toUpperCase()}</DataTableCell>
                <DataTableCell columnKey="num" className="tabular-nums">{formatAmount(item.num)}</DataTableCell>
                <DataTableCell columnKey="st_label">{item.st_label}</DataTableCell>
                <DataTableCell columnKey="remark">{item.remark ?? "—"}</DataTableCell>
                <DataTableCell columnKey="addtime">{item.addtime}</DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      <ConfirmDialog isOpen={pendingDelete} title={t("common.delete")} message={`Delete ${selection.selectedIds.length} record(s)?`} confirmLabel={t("common.delete")} variant="danger" isPending={remove.isPending} onConfirm={async () => { await remove.mutateAsync(selection.selectedIds); selection.clearSelection(); setPendingDelete(false); }} onCancel={() => { if (!remove.isPending) setPendingDelete(false); }} />
    </div>
  );
}
