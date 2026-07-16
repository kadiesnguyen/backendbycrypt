"use client";
import { useMemo, useState } from "react";
import { ActionButton, RowActions } from "@/components/actions";
import { DataTableCell, ActionsCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, UsernameFilter, actionsColumn } from "@/components/list/ListPageParts"
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { AssetListSkeleton } from "./AssetListSkeleton";
import { useAsset, useAssetActions } from "./useAssetActions";
import { useAssets } from "./useAssets";
import type { UserAsset } from "./types";

const BALANCE_FIELDS = ["usdt", "btc", "eth", "ltc", "sol", "xrp"] as const;

export function AssetListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [balances, setBalances] = useState<Record<string, string>>({});
  const [formError, setFormError] = useState<string | null>(null);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useAssets(queryParams);
  const { data: detail } = useAsset(editingId);
  const { update } = useAssetActions();
  const items = data?.data ?? [];
  const meta = data?.meta;

  const openEdit = (item: UserAsset) => {
    setEditingId(item.id);
    const next: Record<string, string> = {};
    for (const field of BALANCE_FIELDS) next[field] = String((item as unknown as Record<string, string>)[field] ?? "0");
    setBalances(next);
    setFormError(null);
  };

  const columns = [
    { key: "username", label: t("common.username") },
    { key: "usdt", label: "USDT" },
    { key: "btc", label: "BTC" },
    { key: "eth", label: "ETH" },
    actionsColumn(t),
  ];

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.assets.title" descriptionKey="page.assets.description" />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      <div className="flex justify-end"><ActionButton variant="ghost" onClick={() => { setUsernameInput(""); updateParams({ username: null, page: "1" }); }}>{t("action.resetSearch")}</ActionButton></div>
      {isLoading ? <AssetListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.assets.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username ?? "—"}</DataTableCell>
                <DataTableCell columnKey="usdt" className="tabular-nums">{formatAmount(item.usdt)}</DataTableCell>
                <DataTableCell columnKey="btc" className="tabular-nums">{formatAmount(item.btc)}</DataTableCell>
                <DataTableCell columnKey="eth" className="tabular-nums">{formatAmount(item.eth)}</DataTableCell>
                <ActionsCell><RowActions><ActionButton onClick={() => openEdit(item)}>{t("common.edit")}</ActionButton></RowActions></ActionsCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      {editingId ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={async (e) => { e.preventDefault(); setFormError(null); try { await update.mutateAsync({ id: editingId, balances }); setEditingId(null); } catch (err) { setFormError(err instanceof Error ? err.message : t("common.saveFailed")); } }} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{t("common.edit")} — {detail?.data?.username ?? ""}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            {BALANCE_FIELDS.map((field) => (
              <input key={field} value={balances[field] ?? ""} onChange={(e) => setBalances((prev) => ({ ...prev, [field]: e.target.value }))} placeholder={field.toUpperCase()} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            ))}
            <div className="flex justify-end gap-2"><ActionButton variant="ghost" type="button" onClick={() => setEditingId(null)}>{t("common.cancel")}</ActionButton><ActionButton variant="primary" type="submit" disabled={update.isPending}>{t("common.save")}</ActionButton></div>
          </form>
        </div>
      ) : null}
    </div>
  );
}
