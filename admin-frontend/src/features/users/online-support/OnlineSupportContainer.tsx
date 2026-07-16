"use client";
import Link from "next/link";
import { useMemo } from "react";
import { ActionButton, RowActions } from "@/components/actions";
import { DataTableCell, ActionsCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, actionsColumn } from "@/components/list/ListPageParts"
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { OnlineSupportSkeleton } from "./OnlineSupportSkeleton";
import { useOnlineSupport } from "./useOnlineSupport";

export function OnlineSupportContainer() {
  const { t } = useI18n();
  const { page, updateParams } = useUrlParams();
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 50 }), [page]);
  const { data, isLoading, isError, error, refetch, isFetching } = useOnlineSupport(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = [
    { key: "username", label: t("common.username") },
    { key: "pending", label: "Pending" },
    actionsColumn(t),
  ];
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.onlineSupport.title" descriptionKey="page.onlineSupport.description" />
      {isLoading ? <OnlineSupportSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.onlineSupport.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="pending">{item.pending_count}</DataTableCell>
                <ActionsCell>
                  <RowActions>
                    <Link href={`/users/online-support/${item.id}`} className="rounded border border-border px-2.5 py-1 text-xs font-medium text-primary hover:bg-surface-elevated">{t("action.view")}</Link>
                    <Link href={`/users/online-support/${item.id}?reply=1`} className="rounded bg-primary px-2.5 py-1 text-xs font-medium text-background hover:opacity-90">{t("action.reply")}</Link>
                  </RowActions>
                </ActionsCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
    </div>
  );
}
