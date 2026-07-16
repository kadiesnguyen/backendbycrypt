"use client";
import { useMemo, useState } from "react";
import { ActionButton, RowActions, ToolbarActions, useRowSelection } from "@/components/actions";
import { DataTableCell, ActionsCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, RowCheckbox, SelectCell, UsernameFilter, actionsColumn } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { walletStatusLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { WalletListSkeleton } from "./WalletListSkeleton";
import { useWallet, useWalletActions } from "./useWalletActions";
import { useWallets } from "./useWallets";
import type { UserWallet } from "./types";

export function WalletListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<UserWallet | null>(null);
  const [formUsername, setFormUsername] = useState("");
  const [formCoin, setFormCoin] = useState("");
  const [formAddr, setFormAddr] = useState("");
  const [formError, setFormError] = useState<string | null>(null);
  const [pendingDelete, setPendingDelete] = useState(false);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useWallets(queryParams);
  const { data: detail } = useWallet(editing?.id ?? null);
  const { create, update, remove } = useWalletActions();
  const items = data?.data ?? [];
  const meta = data?.meta;
  const selection = useRowSelection(items);

  const openCreate = () => { setEditing(null); setFormUsername(""); setFormCoin(""); setFormAddr(""); setFormError(null); setFormOpen(true); };
  const openEdit = (item: UserWallet) => { setEditing(item); setFormUsername(item.username ?? ""); setFormCoin(item.name); setFormAddr(item.addr); setFormError(null); setFormOpen(true); };

  const columns = [
    { key: "username", label: t("common.username") },
    { key: "coinname", label: t("common.coin") },
    { key: "addr", label: "Address" },
    { key: "status", label: t("common.status") },
    actionsColumn(t),
  ];

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.wallets.title" descriptionKey="page.wallets.description" action={<ToolbarActions><ActionButton variant="primary" onClick={openCreate}>{t("action.add")}</ActionButton><ActionButton variant="danger" disabled={selection.selectedIds.length === 0} onClick={() => setPendingDelete(true)}>{t("common.delete")}</ActionButton></ToolbarActions>} />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <WalletListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.wallets.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns} selectable allSelected={selection.allSelected} someSelected={selection.someSelected} onToggleAll={selection.toggleAll}>
            {items.map((item) => (
              <tr key={item.id}>
                <SelectCell><RowCheckbox checked={selection.isSelected(item.id)} onChange={() => selection.toggleOne(item.id)} /></SelectCell>
                <DataTableCell columnKey="username" className="break-all">{item.username ?? "—"}</DataTableCell>
                <DataTableCell columnKey="coinname">{item.coinname?.toUpperCase() ?? item.name}</DataTableCell>
                <DataTableCell columnKey="addr" className="break-all">{item.addr}</DataTableCell>
                <DataTableCell columnKey="status">{walletStatusLabel(t, item.status)}</DataTableCell>
                <ActionsCell><RowActions><ActionButton onClick={() => openEdit(item)}>{t("common.edit")}</ActionButton></RowActions></ActionsCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      {formOpen ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={async (e) => { e.preventDefault(); setFormError(null); const payload = { username: formUsername, name: formCoin, addr: formAddr }; try { if (editing) await update.mutateAsync({ id: editing.id, payload }); else await create.mutateAsync(payload); setFormOpen(false); } catch (err) { setFormError(err instanceof Error ? err.message : t("common.saveFailed")); } }} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{editing ? t("common.edit") : t("action.add")}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            {!editing ? <input required value={formUsername} onChange={(e) => setFormUsername(e.target.value)} placeholder={t("common.username")} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /> : null}
            <input required value={formCoin} onChange={(e) => setFormCoin(e.target.value)} placeholder={t("common.coin")} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            <input required value={formAddr} onChange={(e) => setFormAddr(e.target.value)} placeholder="Address" className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            <div className="flex justify-end gap-2"><ActionButton variant="ghost" type="button" onClick={() => setFormOpen(false)}>{t("common.cancel")}</ActionButton><ActionButton variant="primary" type="submit" disabled={create.isPending || update.isPending}>{t("common.save")}</ActionButton></div>
          </form>
        </div>
      ) : null}
      <ConfirmDialog isOpen={pendingDelete} title={t("common.delete")} message={`Delete ${selection.selectedIds.length} wallet(s)?`} confirmLabel={t("common.delete")} variant="danger" isPending={remove.isPending} onConfirm={async () => { await remove.mutateAsync(selection.selectedIds); selection.clearSelection(); setPendingDelete(false); }} onCancel={() => { if (!remove.isPending) setPendingDelete(false); }} />
    </div>
  );
}
