"use client";

import { useQuery } from "@tanstack/react-query";
import { EmptyState, PaginationNav } from "@/components/list/ListPageParts";
import { ErrorState } from "@/components/ui/ErrorState";
import { TableShell, tableClassName, thClassName, theadClassName } from "@/components/list/TableShell";
import { useI18n } from "@/lib/i18n/useI18n";
import { Suspense, useCallback } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { fetchPerpFills } from "./api";
import { perpFillsQueryKey } from "./query-keys";
import { PerpPositionListSkeleton } from "./PerpPositionListSkeleton";

function FillsViewWithPagination({
  page,
  onPageChange,
}: {
  page: number;
  onPageChange: (p: number) => void;
}) {
  const { t } = useI18n();
  const { data, isLoading, isError, error, refetch, isFetching } = useQuery({
    queryKey: [...perpFillsQueryKey, page],
    queryFn: () => fetchPerpFills({ page, per_page: 15 }),
  });

  if (isLoading) return <PerpPositionListSkeleton />;
  if (isError) {
    return (
      <ErrorState
        message={error instanceof Error ? error.message : t("common.loadFailed")}
        retry={() => refetch()}
      />
    );
  }

  const fills = data?.data ?? [];
  if (fills.length === 0) return <EmptyState titleKey="page.perp.fillsEmpty" />;

  return (
    <>
      <TableShell>
        <table className={tableClassName}>
          <thead className={theadClassName}>
            <tr>
              <th className={thClassName}>ID</th>
              <th className={thClassName}>{t("common.coin")}</th>
              <th className={thClassName}>{t("page.perp.action")}</th>
              <th className={thClassName}>{t("page.perp.qty")}</th>
              <th className={thClassName}>{t("page.perp.price")}</th>
              <th className={thClassName}>PnL</th>
              <th className={thClassName}>{t("common.time")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-border">
            {fills.map((f) => (
              <tr key={f.id}>
                <td className="px-3 py-2 text-sm tabular-nums">{f.id}</td>
                <td className="px-3 py-2 text-sm uppercase">{f.symbol}</td>
                <td className="px-3 py-2 text-sm">{f.action}</td>
                <td className="px-3 py-2 text-sm tabular-nums">{f.qty}</td>
                <td className="px-3 py-2 text-sm tabular-nums">{f.price}</td>
                <td className="px-3 py-2 text-sm tabular-nums">{f.pnl}</td>
                <td className="px-3 py-2 text-sm text-muted">{f.created_at}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </TableShell>
      {data?.meta ? (
        <PaginationNav meta={data.meta} isFetching={isFetching} onPageChange={onPageChange} />
      ) : null}
    </>
  );
}

function FillsPaginated() {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  const page = Number(searchParams.get("page") ?? "1");

  const onPageChange = useCallback(
    (p: number) => {
      const next = new URLSearchParams(searchParams.toString());
      next.set("page", String(p));
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  return <FillsViewWithPagination page={page} onPageChange={onPageChange} />;
}

export function PerpFillsContainer() {
  return (
    <Suspense fallback={<PerpPositionListSkeleton />}>
      <FillsPaginated />
    </Suspense>
  );
}
