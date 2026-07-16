"use client";
import { useMemo, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { DataTableCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, UsernameFilter } from "@/components/list/ListPageParts"
import { transferStatusLabel } from "@/lib/i18n/entity-labels";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { TransferListSkeleton } from "./TransferListSkeleton";
import { useTransfers } from "./useTransfers";

export function TransferListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useTransfers(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = [
    { key: "username", label: t("common.username") },
    { key: "from", label: "From" }, { key: "to", label: "To" },
    { key: "amount", label: t("common.amount") }, { key: "status_label", label: t("common.status") },
    { key: "addtime", label: t("common.time") },
  ];
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.transfers.title" descriptionKey="page.transfers.description" />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <TransferListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.transfers.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="from" className="tabular-nums">
                  {item.from_coin?.toUpperCase()} {formatAmount(item.from_amount)}
                </DataTableCell>
                <DataTableCell columnKey="to" className="tabular-nums">
                  {item.to_coin?.toUpperCase()} {formatAmount(item.to_amount)}
                </DataTableCell>
                <DataTableCell columnKey="amount" className="tabular-nums">{formatAmount(item.convert_rate)}</DataTableCell>
                <DataTableCell columnKey="status_label">
                  {transferStatusLabel(t, item.status)}
                </DataTableCell>
                <DataTableCell columnKey="addtime">{item.addtime}</DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
    </div>
  );
}
