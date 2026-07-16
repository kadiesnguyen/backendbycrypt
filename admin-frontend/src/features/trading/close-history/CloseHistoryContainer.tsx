"use client";
import { useMemo, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { DataTableCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, UsernameFilter } from "@/components/list/ListPageParts"
import { contractIsWinLabel, contractStatusLabel } from "@/lib/i18n/entity-labels";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { CloseHistorySkeleton } from "./CloseHistorySkeleton";
import { useCloseHistory } from "./useCloseHistory";

function formatDateTime(value?: string | null): string {
  if (!value) return "—";
  const normalized = value.includes("T") ? value : value.replace(" ", "T");
  const date = new Date(normalized.endsWith("Z") ? normalized : `${normalized}Z`);
  if (Number.isNaN(date.getTime())) return value.replace("T", " ").replace(".000000Z", "");
  return date.toLocaleString("vi-VN", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });
}

function profitLossClass(value: number | null | undefined): string {
  if (value == null) return "text-muted";
  if (value > 0) return "text-success";
  if (value < 0) return "text-danger";
  return "text-muted";
}

export function CloseHistoryContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useCloseHistory(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = [
    { key: "username", label: t("common.username"), className: "w-[16%]" },
    { key: "coinname", label: t("common.coin"), className: "w-[8%]" },
    { key: "num", label: t("common.amount"), className: "w-[8%]" },
    { key: "balance_before", label: t("page.closeHistory.balanceBefore"), className: "w-[12%]" },
    { key: "balance_after", label: t("page.closeHistory.balanceAfter"), className: "w-[12%]" },
    { key: "profit_loss", label: t("page.closeHistory.profitLoss"), className: "w-[10%]" },
    { key: "status_label", label: t("common.status"), className: "w-[18%]" },
  ];
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.closeHistory.title" descriptionKey="page.closeHistory.description" />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <CloseHistorySkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.closeHistory.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="coinname">{item.coinname?.toUpperCase()}</DataTableCell>
                <DataTableCell columnKey="num" className="tabular-nums">{formatAmount(item.num)}</DataTableCell>
                <DataTableCell columnKey="balance_before" className="tabular-nums">
                  {item.balance_before != null ? formatAmount(item.balance_before) : "—"}
                </DataTableCell>
                <DataTableCell columnKey="balance_after" className="tabular-nums">
                  {item.balance_after != null ? formatAmount(item.balance_after) : "—"}
                </DataTableCell>
                <DataTableCell columnKey="profit_loss" className={`tabular-nums font-medium ${profitLossClass(item.profit_loss)}`}>
                  {item.profit_loss != null ? formatAmount(item.profit_loss) : "—"}
                </DataTableCell>
                <DataTableCell columnKey="status_label">
                  <div className="space-y-1">
                    <div className="font-medium">{contractStatusLabel(t, item.status)}</div>
                    <div className="text-xs text-muted">{contractIsWinLabel(t, item.is_win)}</div>
                    <div className="text-xs text-muted">{formatDateTime(item.selltime ?? item.buytime)}</div>
                  </div>
                </DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
    </div>
  );
}
