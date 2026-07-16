"use client";

import { useMemo, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import {
  DataTableCell,
  DataTable,
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
  UsernameFilter,
} from "@/components/list/ListPageParts";
import { formatCompactTimestamp } from "@/features/trading/lib/format";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { FrozenProfitListSkeleton } from "./FrozenProfitListSkeleton";
import { useMinerRows } from "./useMiners";

export function FrozenProfitListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }),
    [page, username],
  );
  const { data, isLoading, isError, error, refetch, isFetching } = useMinerRows(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;

  const columns = [
    { key: "username", label: t("common.username") },
    { key: "detail", label: "Detail" },
    { key: "amount", label: t("common.amount") },
    { key: "addtime", label: t("common.time") },
  ];

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.frozenProfits.title" descriptionKey="page.frozenProfits.description" />
      <UsernameFilter
        value={usernameInput}
        onChange={setUsernameInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ username: usernameInput.trim() || null, page: "1" });
        }}
      />
      {isLoading ? <FrozenProfitListSkeleton /> : null}
      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("common.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState titleKey="page.frozenProfits.noResults" descriptionKey="common.noResultsHint" />
      ) : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">
                  {item.username}
                </DataTableCell>
                <DataTableCell columnKey="detail">
                  {item.kjtitle ?? item.ktitle ?? item.status_label ?? "—"}
                </DataTableCell>
                <DataTableCell columnKey="amount" className="tabular-nums">
                  {item.num != null ? formatAmount(item.num) : "—"} {item.coin ?? ""}
                </DataTableCell>
                <DataTableCell columnKey="addtime">{formatCompactTimestamp(item.addtime)}</DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}
    </div>
  );
}
