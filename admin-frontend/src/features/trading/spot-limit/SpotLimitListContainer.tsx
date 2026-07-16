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
import { spotOrderStatusLabel } from "@/lib/i18n/entity-labels";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { SpotLimitListSkeleton } from "./SpotLimitListSkeleton";
import { useSpotLimit } from "./useSpot";

function spotOrderColumns(t: (key: string) => string) {
  return [
    { key: "account", label: t("common.username"), className: "w-[24%]" },
    { key: "symbol", label: t("common.symbol"), className: "w-[12%]" },
    { key: "coinnum", label: t("common.amount"), className: "w-[10%]" },
    { key: "status_label", label: t("common.status"), className: "w-[14%]" },
    { key: "addtime", label: t("common.time"), className: "w-[20%]" },
  ];
}

export function SpotLimitListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }),
    [page, username],
  );
  const { data, isLoading, isError, error, refetch, isFetching } = useSpotLimit(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = useMemo(() => spotOrderColumns(t), [t]);

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.spotLimit.title" descriptionKey="page.spotLimit.description" />
      <UsernameFilter
        value={usernameInput}
        onChange={setUsernameInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ username: usernameInput.trim() || null, page: "1" });
        }}
      />
      {isLoading ? <SpotLimitListSkeleton /> : null}
      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("common.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState titleKey="page.spotLimit.noResults" descriptionKey="common.noResultsHint" />
      ) : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="account" className="break-all">{item.account}</DataTableCell>
                <DataTableCell columnKey="symbol">{item.symbol}</DataTableCell>
                <DataTableCell columnKey="coinnum" className="tabular-nums">{formatAmount(item.coinnum)}</DataTableCell>
                <DataTableCell columnKey="status_label">
                  {spotOrderStatusLabel(t, item.status)}
                </DataTableCell>
                <DataTableCell columnKey="addtime">{item.addtime}</DataTableCell>
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
