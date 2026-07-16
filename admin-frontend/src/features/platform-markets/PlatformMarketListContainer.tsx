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
} from "@/components/list/ListPageParts";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { PlatformMarketListSkeleton } from "./PlatformMarketListSkeleton";
import { usePlatformMarket, usePlatformMarketActions, usePlatformMarkets } from "./usePlatformMarkets";
import type { PlatformMarket, PlatformMarketPayload } from "./types";

export function PlatformMarketListContainer() {
  const { t } = useI18n();
  const { page, updateParams } = useUrlParams();
  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [name, setName] = useState("");
  const [newPrice, setNewPrice] = useState("");
  const [formError, setFormError] = useState<string | null>(null);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15 }), [page]);
  const { data, isLoading, isError, error, refetch, isFetching } = usePlatformMarkets(queryParams);
  usePlatformMarket(formOpen ? editingId : null);
  const { create, update } = usePlatformMarketActions();
  const items = data?.data ?? [];
  const meta = data?.meta;

  const columns = [
    { key: "name", label: "Name" },
    { key: "new_price", label: "Price" },
    { key: "volume", label: "Volume" },
    { key: "change", label: "Change" },
    { key: "actions", label: t("common.actions") },
  ];

  const openEdit = (item: PlatformMarket) => {
    setEditingId(item.id);
    setName(item.name);
    setNewPrice(item.new_price);
    setFormOpen(true);
  };

  const openCreate = () => {
    setEditingId(null);
    setName("");
    setNewPrice("");
    setFormOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);
    const payload: PlatformMarketPayload = { name, new_price: newPrice };
    try {
      if (editingId) await update.mutateAsync({ id: editingId, payload });
      else await create.mutateAsync(payload);
      setFormOpen(false);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
    }
  };

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey="page.platformMarkets.title"
        descriptionKey="page.platformMarkets.description"
        action={
          <button type="button" onClick={openCreate} className="rounded bg-primary px-4 py-2 text-sm text-background">
            {t("common.create")}
          </button>
        }
      />
      {isLoading ? <PlatformMarketListSkeleton /> : null}
      {isError ? (
        <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState titleKey="page.platformMarkets.noResults" descriptionKey="common.noResultsHint" />
      ) : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="name">{item.name}</DataTableCell>
                <DataTableCell columnKey="new_price" className="tabular-nums">{formatAmount(item.new_price)}</DataTableCell>
                <DataTableCell columnKey="volume" className="tabular-nums">{formatAmount(item.volume)}</DataTableCell>
                <DataTableCell columnKey="change" className="tabular-nums">{formatAmount(item.change)}</DataTableCell>
                <DataTableCell columnKey="actions" actions>
                  <button type="button" onClick={() => openEdit(item)} className="text-sm text-primary">
                    {t("common.edit")}
                  </button>
                </DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? (
            <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} />
          ) : null}
        </>
      ) : null}
      {formOpen ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={handleSave} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{editingId ? t("common.edit") : t("common.create")}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            <input
              placeholder="Name"
              required
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <input
              placeholder="New price"
              value={newPrice}
              onChange={(e) => setNewPrice(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <div className="flex justify-end gap-2">
              <button type="button" onClick={() => setFormOpen(false)} className="rounded border border-border px-4 py-2 text-sm">
                {t("common.cancel")}
              </button>
              <button type="submit" className="rounded bg-primary px-4 py-2 text-sm text-background">
                {t("common.save")}
              </button>
            </div>
          </form>
        </div>
      ) : null}
    </div>
  );
}
