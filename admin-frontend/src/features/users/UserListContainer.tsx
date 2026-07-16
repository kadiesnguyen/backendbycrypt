"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { ActionButton, RowActions, ToolbarActions, useRowSelection } from "@/components/actions";
import { DataTableCell, ActionsCell,
  DataTable,
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
  RowCheckbox,
  SelectCell,
  actionsColumn, } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { UserListSkeleton } from "./UserListSkeleton";
import { useUserActions } from "./useUserActions";
import { useUsers } from "./useUsers";
import { UserFormDialog } from "./UserFormDialog";
import { TradeLockDialog, DEFAULT_TRADE_LOCK_MESSAGE } from "./TradeLockDialog";
import { KycReviewDialog } from "./KycReviewDialog";
import { KycViewDialog } from "./KycViewDialog";
import type { UserUpsertPayload } from "./api";
import type { AdminUser } from "./types";

type DialogMode = "create" | "edit" | "funds" | "notice" | "tradeLock" | null;
type PendingBulk = { type: 1 | 2 | 3 | 4 | 5; label: string } | null;

export function UserListContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");
  const username = searchParams.get("username") ?? "";
  const statusParam = searchParams.get("status");
  const status = statusParam !== null && statusParam !== "" ? Number(statusParam) : undefined;

  const [usernameInput, setUsernameInput] = useState(username);
  const [dialogMode, setDialogMode] = useState<DialogMode>(null);
  const [activeUser, setActiveUser] = useState<AdminUser | null>(null);
  const [pendingBulk, setPendingBulk] = useState<PendingBulk>(null);
  const [formError, setFormError] = useState<string | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);
  const [actionSuccess, setActionSuccess] = useState<string | null>(null);
  const [pendingActionId, setPendingActionId] = useState<number | null>(null);
  const [kycUserId, setKycUserId] = useState<number | null>(null);
  const [kycViewUserId, setKycViewUserId] = useState<number | null>(null);

  const [fundAmount, setFundAmount] = useState("");
  const [noticeTitle, setNoticeTitle] = useState("");
  const [noticeContent, setNoticeContent] = useState("");
  const [tradeLockMessage, setTradeLockMessage] = useState(DEFAULT_TRADE_LOCK_MESSAGE);

  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined, status }),
    [page, username, status],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = useUsers(queryParams);
  const { create, update, updateStatus, updateTradeLock, addFunds, kycReview, setAgent, cancelAgent } = useUserActions();

  const users = data?.data ?? [];
  const meta = data?.meta;
  const selection = useRowSelection(users);

  useEffect(() => {
    if (!actionSuccess) return;
    const timer = window.setTimeout(() => setActionSuccess(null), 5000);
    return () => window.clearTimeout(timer);
  }, [actionSuccess]);

  useEffect(() => {
    if (!actionError) return;
    const timer = window.setTimeout(() => setActionError(null), 5000);
    return () => window.clearTimeout(timer);
  }, [actionError]);

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") next.delete(key);
        else next.set(key, value);
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  const openEdit = (user: AdminUser) => {
    setActiveUser(user);
    setFormError(null);
    setDialogMode("edit");
  };

  const openCreate = () => {
    setActiveUser(null);
    setFormError(null);
    setDialogMode("create");
  };

  const handleSaveUser = async (payload: UserUpsertPayload) => {
    setFormError(null);
    try {
      if (dialogMode === "create") await create.mutateAsync(payload);
      else if (activeUser) await update.mutateAsync({ id: activeUser.id, payload });
      setDialogMode(null);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
      throw err;
    }
  };

  const handleBulk = async () => {
    if (!pendingBulk || selection.selectedIds.length === 0) return;

    const { type, label } = pendingBulk;
    const count = selection.selectedIds.length;

    setActionError(null);
    setActionSuccess(null);

    try {
      await updateStatus.mutateAsync({ ids: selection.selectedIds, type });
      selection.clearSelection();
      setPendingBulk(null);
      setActionSuccess(t("page.users.bulkActionSuccess", { action: label, count: String(count) }));
    } catch (err) {
      setPendingBulk(null);
      setActionError(
        err instanceof Error
          ? err.message
          : t("page.users.bulkActionFailed", { action: label }),
      );
    }
  };

  const columns = [
    { key: "username", label: t("common.username") },
    { key: "assets", label: t("common.assets") },
    { key: "status", label: t("common.status") },
    { key: "verification", label: t("common.verification") },
    { key: "invit", label: t("common.invite") },
    actionsColumn(t),
  ];

  const formatAsset = (value: string | undefined) => formatAmount(value ?? 0);

  const renderVerification = (user: AdminUser) => {
    const rz = user.rzstatus ?? 0;
    if (rz === 2) {
      return (
        <button
          type="button"
          onClick={() => setKycViewUserId(user.id)}
          className="text-sm font-medium text-success transition hover:underline"
        >
          {t("kyc.verified")}
        </button>
      );
    }
    if (rz === 1) {
      return <span className="text-sm text-primary">{t("kyc.pending")}</span>;
    }
    if (rz === 3) {
      return <span className="text-sm text-danger">{t("kyc.rejected")}</span>;
    }
    return <span className="text-sm text-muted">{t("kyc.notVerified")}</span>;
  };

  const toolbar = (
    <ToolbarActions>
      <ActionButton variant="primary" onClick={openCreate}>{t("action.add")}</ActionButton>
      <ActionButton variant="warning" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk({ type: 1, label: t("action.freeze") })}>{t("action.freeze")}</ActionButton>
      <ActionButton variant="success" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk({ type: 2, label: t("action.unfreeze") })}>{t("action.unfreeze")}</ActionButton>
      <ActionButton variant="success" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk({ type: 3, label: t("action.enableWithdraw") })}>{t("action.enableWithdraw")}</ActionButton>
      <ActionButton variant="warning" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk({ type: 4, label: t("action.disableWithdraw") })}>{t("action.disableWithdraw")}</ActionButton>
      <ActionButton variant="danger" disabled={selection.selectedIds.length === 0} onClick={() => setPendingBulk({ type: 5, label: t("common.delete") })}>{t("common.delete")}</ActionButton>
      <ActionButton variant="primary" onClick={() => { setNoticeTitle(""); setNoticeContent(""); setDialogMode("notice"); }}>{t("action.sendSystemNotice")}</ActionButton>
    </ToolbarActions>
  );

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.users.title" descriptionKey="page.users.description" action={toolbar} />

      <form onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} className="flex flex-col gap-3 rounded-lg border border-border bg-surface p-4 sm:flex-row sm:items-end" role="search">
        <div className="flex-1">
          <label htmlFor="user-search" className="block text-sm font-medium text-foreground">{t("common.username")}</label>
          <input id="user-search" type="search" value={usernameInput} onChange={(e) => setUsernameInput(e.target.value)} placeholder={t("common.searchByUsername")} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground" />
        </div>
        <div className="w-full sm:w-40">
          <label htmlFor="user-status" className="block text-sm font-medium text-foreground">{t("common.status")}</label>
          <select id="user-status" value={statusParam ?? ""} onChange={(e) => updateParams({ status: e.target.value || null, page: "1" })} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground">
            <option value="">{t("common.all")}</option>
            <option value="1">{t("userForm.statusNormal")}</option>
            <option value="2">{t("userForm.statusFreeze")}</option>
          </select>
        </div>
        <ActionButton variant="primary" type="submit">{t("common.search")}</ActionButton>
        <ActionButton variant="ghost" type="button" onClick={() => { setUsernameInput(""); updateParams({ username: null, status: null, page: "1" }); }}>{t("action.resetSearch")}</ActionButton>
      </form>

      {actionSuccess ? (
        <div role="status" className="rounded-lg border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
          {actionSuccess}
        </div>
      ) : null}
      {actionError ? <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">{actionError}</div> : null}
      {isLoading ? <UserListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && users.length === 0 ? <EmptyState titleKey="page.users.noResults" descriptionKey="common.noResultsHint" action={<ActionButton variant="primary" onClick={openCreate}>{t("action.add")}</ActionButton>} /> : null}

      {!isLoading && !isError && users.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns} selectable allSelected={selection.allSelected} someSelected={selection.someSelected} onToggleAll={selection.toggleAll}>
            {users.map((user) => {
              const busy = pendingActionId === user.id;
              return (
                <tr key={user.id}>
                  <SelectCell><RowCheckbox checked={selection.isSelected(user.id)} onChange={() => selection.toggleOne(user.id)} label={user.username} /></SelectCell>
                  <DataTableCell columnKey="username" className="break-all font-medium">{user.username}</DataTableCell>
                  <DataTableCell columnKey="assets" className="text-xs text-muted">
                    <div>USDT: <span className="text-foreground">{formatAsset(user.usdt)}</span></div>
                    <div>BTC: <span className="text-foreground">{formatAsset(user.btc)}</span></div>
                    <div>ETH: <span className="text-foreground">{formatAsset(user.eth)}</span></div>
                  </DataTableCell>
                  <DataTableCell columnKey="status">{user.status === 1 ? t("userForm.statusNormal") : t("userForm.statusFreeze")}</DataTableCell>
                  <DataTableCell columnKey="verification">{renderVerification(user)}</DataTableCell>
                  <DataTableCell columnKey="invit" className="font-mono text-sm">{user.invit || "—"}</DataTableCell>
                  <ActionsCell>
                    <RowActions>
                      <ActionButton disabled={busy} onClick={() => openEdit(user)}>{t("common.edit")}</ActionButton>
                      {user.rzstatus === 1 ? (
                        <ActionButton variant="success" disabled={busy} onClick={() => setKycUserId(user.id)}>{t("action.verifyReview")}</ActionButton>
                      ) : null}
                      <ActionButton disabled={busy} onClick={() => { setActiveUser(user); setNoticeTitle(""); setNoticeContent(""); setDialogMode("notice"); }}>{t("action.sendNotice")}</ActionButton>
                      <ActionButton variant="primary" disabled={busy} onClick={() => { setActiveUser(user); setFundAmount(""); setDialogMode("funds"); }}>{t("action.addUsdt")}</ActionButton>
                      {user.trade_locked === 1 ? (
                        <ActionButton
                          variant="success"
                          disabled={busy}
                          onClick={async () => {
                            setPendingActionId(user.id);
                            setActionError(null);
                            try {
                              await updateTradeLock.mutateAsync({ id: user.id, locked: false });
                              setActionSuccess(t("page.users.tradeUnlockSuccess", { username: user.username }));
                            } catch (err) {
                              setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
                            } finally {
                              setPendingActionId(null);
                            }
                          }}
                        >
                          {t("action.unlockTrade")}
                        </ActionButton>
                      ) : (
                        <ActionButton
                          variant="warning"
                          disabled={busy}
                          onClick={() => {
                            setActiveUser(user);
                            setTradeLockMessage(DEFAULT_TRADE_LOCK_MESSAGE);
                            setFormError(null);
                            setDialogMode("tradeLock");
                          }}
                        >
                          {t("action.lockTrade")}
                        </ActionButton>
                      )}
                      {user.is_agent === 1 ? (
                        <ActionButton variant="danger" disabled={busy} onClick={async () => { setPendingActionId(user.id); try { await cancelAgent.mutateAsync(user.id); } catch (err) { setActionError(err instanceof Error ? err.message : t("common.actionFailed")); } finally { setPendingActionId(null); } }}>{t("action.cancelAgent")}</ActionButton>
                      ) : (
                        <ActionButton variant="success" disabled={busy} onClick={async () => { setPendingActionId(user.id); try { await setAgent.mutateAsync(user.id); } catch (err) { setActionError(err instanceof Error ? err.message : t("common.actionFailed")); } finally { setPendingActionId(null); } }}>{t("action.setAgent")}</ActionButton>
                      )}
                    </RowActions>
                  </ActionsCell>
                </tr>
              );
            })}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}

      {(dialogMode === "create" || dialogMode === "edit") ? (
        <UserFormDialog
          mode={dialogMode}
          userId={activeUser?.id}
          onClose={() => setDialogMode(null)}
          onSubmit={handleSaveUser}
          isPending={create.isPending || update.isPending}
          error={formError}
        />
      ) : null}

      {kycUserId !== null ? (
        <KycReviewDialog
          userId={kycUserId}
          onClose={() => { setKycUserId(null); setFormError(null); }}
          isPending={kycReview.isPending}
          error={formError}
          onReview={async (payload) => {
            setFormError(null);
            try {
              await kycReview.mutateAsync({ id: kycUserId, payload });
              setKycUserId(null);
              void refetch();
            } catch (err) {
              setFormError(err instanceof Error ? err.message : t("common.actionFailed"));
            }
          }}
        />
      ) : null}

      {kycViewUserId !== null ? (
        <KycViewDialog userId={kycViewUserId} onClose={() => setKycViewUserId(null)} />
      ) : null}

      {dialogMode === "funds" && activeUser ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={async (e) => { e.preventDefault(); setFormError(null); try { await addFunds.mutateAsync({ id: activeUser.id, amount: fundAmount }); setDialogMode(null); setActionError(null); setActionSuccess(t("page.users.addFundsSuccess", { username: activeUser.username, amount: fundAmount })); } catch (err) { setFormError(err instanceof Error ? err.message : t("common.actionFailed")); } }} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{t("action.addUsdt")} — {activeUser.username}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            <input required value={fundAmount} onChange={(e) => setFundAmount(e.target.value)} placeholder={t("common.amount")} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            <div className="flex justify-end gap-2"><ActionButton variant="ghost" type="button" onClick={() => setDialogMode(null)}>{t("common.cancel")}</ActionButton><ActionButton variant="primary" type="submit" disabled={addFunds.isPending}>{t("common.confirm")}</ActionButton></div>
          </form>
        </div>
      ) : null}

      {dialogMode === "notice" ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={async (e) => { e.preventDefault(); setFormError(null); try { const { sendNotice } = await import("./notices/api"); await sendNotice({ type: activeUser ? 1 : 2, user_id: activeUser?.id, title: noticeTitle, content: noticeContent }); setDialogMode(null); } catch (err) { setFormError(err instanceof Error ? err.message : t("common.actionFailed")); } }} className="w-full max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{activeUser ? `${t("action.sendNotice")} — ${activeUser.username}` : t("action.sendSystemNotice")}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            <input required value={noticeTitle} onChange={(e) => setNoticeTitle(e.target.value)} placeholder={t("common.title")} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            <textarea required rows={4} value={noticeContent} onChange={(e) => setNoticeContent(e.target.value)} placeholder={t("common.content")} className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
            <div className="flex justify-end gap-2"><ActionButton variant="ghost" type="button" onClick={() => setDialogMode(null)}>{t("common.cancel")}</ActionButton><ActionButton variant="primary" type="submit">{t("common.confirm")}</ActionButton></div>
          </form>
        </div>
      ) : null}

      {dialogMode === "tradeLock" && activeUser ? (
        <TradeLockDialog
          username={activeUser.username}
          message={tradeLockMessage}
          onMessageChange={setTradeLockMessage}
          onClose={() => setDialogMode(null)}
          isPending={updateTradeLock.isPending}
          error={formError}
          onConfirm={async () => {
            setFormError(null);
            try {
              await updateTradeLock.mutateAsync({
                id: activeUser.id,
                locked: true,
                message: tradeLockMessage.trim() || DEFAULT_TRADE_LOCK_MESSAGE,
              });
              setDialogMode(null);
              setActionSuccess(t("page.users.tradeLockSuccess", { username: activeUser.username }));
            } catch (err) {
              setFormError(err instanceof Error ? err.message : t("common.actionFailed"));
              throw err;
            }
          }}
        />
      ) : null}

      <ConfirmDialog isOpen={pendingBulk !== null} title={pendingBulk?.label ?? ""} message={pendingBulk ? t("page.users.bulkConfirm", { action: pendingBulk.label, count: String(selection.selectedIds.length) }) : ""} confirmLabel={t("common.confirm")} variant={pendingBulk?.type === 5 ? "danger" : "default"} isPending={updateStatus.isPending} onConfirm={handleBulk} onCancel={() => { if (!updateStatus.isPending) setPendingBulk(null); }} />
    </div>
  );
}
