"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";

import { useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { CoinFormDialog } from "./CoinFormDialog";
import { CoinList } from "./CoinList";
import { CoinListSkeleton } from "./CoinListSkeleton";
import { useCoin, useCoins } from "./useCoins";
import { useCoinActions } from "./useCoinActions";
import type { AdminCoin, CoinUpsertPayload } from "./types";

export function CoinListContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");

  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [pendingDelete, setPendingDelete] = useState<AdminCoin | null>(null);
  const [formError, setFormError] = useState<string | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);
  const [pendingActionId, setPendingActionId] = useState<number | null>(null);

  const queryParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 15,
    }),
    [page],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = useCoins(queryParams);
  const { data: detailData, isLoading: isLoadingDetail } = useCoin(formOpen ? editingId : null);
  const { create, update, updateStatus } = useCoinActions();

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") {
          next.delete(key);
        } else {
          next.set(key, value);
        }
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  const handleOpenCreate = () => {
    setFormError(null);
    setEditingId(null);
    setFormOpen(true);
  };

  const handleOpenEdit = (item: AdminCoin) => {
    setFormError(null);
    setEditingId(item.id);
    setFormOpen(true);
  };

  const handleFormSubmit = async (payload: CoinUpsertPayload) => {
    setFormError(null);

    try {
      if (editingId) {
        await update.mutateAsync({ id: editingId, payload });
      } else {
        await create.mutateAsync(payload);
      }
      setFormOpen(false);
      setEditingId(null);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : "Save failed.");
    }
  };

  const handleToggleStatus = async (item: AdminCoin) => {
    setActionError(null);
    setPendingActionId(item.id);

    try {
      await updateStatus.mutateAsync({
        ids: [item.id],
        type: item.status === 1 ? "forbid" : "resume",
      });
    } catch (err) {
      setActionError(err instanceof Error ? err.message : "Status update failed.");
    } finally {
      setPendingActionId(null);
    }
  };

  const handleConfirmDelete = async () => {
    if (!pendingDelete) return;

    setActionError(null);
    setPendingActionId(pendingDelete.id);

    try {
      await updateStatus.mutateAsync({ ids: [pendingDelete.id], type: "delt" });
      setPendingDelete(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : "Delete failed.");
    } finally {
      setPendingActionId(null);
    }
  };

  const items = data?.data ?? [];
  const meta = data?.meta;
  const editingItem = editingId ? (detailData?.data ?? null) : null;
  const isFormPending = create.isPending || update.isPending;

  const headerAction = (
    <button
      type="button"
      onClick={handleOpenCreate}
      className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
    >
      {t("common.create")}
    </button>
  );

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.coins.title" descriptionKey="page.coins.description" action={headerAction} />

      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <CoinListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : "Failed to load coins."}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && items.length === 0 ? (
        <div role="status" className="rounded-lg border border-border bg-surface px-6 py-12 text-center">
          <h2 className="text-sm font-medium text-foreground">No coins configured</h2>
          <p className="mt-1 text-sm text-muted">Add a coin to enable deposits and trading.</p>
          <button
            type="button"
            onClick={handleOpenCreate}
            className="mt-4 rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
          >
            Add coin
          </button>
        </div>
      ) : null}

      {!isLoading && !isError && items.length > 0 ? (
        <>
          <div className="flex items-center justify-between text-sm text-muted">
            <p>
              {meta ? (
                <>
                  Showing page {meta.current_page} of {meta.last_page} — {meta.total} total
                </>
              ) : null}
              {isFetching ? <span className="ml-2 text-primary">Updating…</span> : null}
            </p>
          </div>
          <CoinList
            items={items}
            onEdit={handleOpenEdit}
            onToggleStatus={handleToggleStatus}
            onDelete={(item) => {
              setActionError(null);
              setPendingDelete(item);
            }}
            pendingId={pendingActionId}
          />
          {meta && meta.last_page > 1 ? (
            <nav aria-label="Pagination" className="flex items-center justify-center gap-2">
              <button
                type="button"
                disabled={meta.current_page <= 1}
                onClick={() => updateParams({ page: String(meta.current_page - 1) })}
                className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
              >
                Previous
              </button>
              <span className="px-2 text-sm text-muted">
                {meta.current_page} / {meta.last_page}
              </span>
              <button
                type="button"
                disabled={meta.current_page >= meta.last_page}
                onClick={() => updateParams({ page: String(meta.current_page + 1) })}
                className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
              >
                Next
              </button>
            </nav>
          ) : null}
        </>
      ) : null}

      <CoinFormDialog
        isOpen={formOpen}
        editingItem={editingItem}
        isLoadingDetail={Boolean(editingId && isLoadingDetail)}
        isPending={isFormPending}
        error={formError}
        onSubmit={handleFormSubmit}
        onClose={() => {
          if (!isFormPending) {
            setFormOpen(false);
            setEditingId(null);
            setFormError(null);
          }
        }}
      />

      <ConfirmDialog
        isOpen={pendingDelete !== null}
        title="Delete coin"
        message={
          pendingDelete
            ? `Delete "${pendingDelete.name}"? This removes the coin and its user balance columns. This cannot be undone.`
            : ""
        }
        confirmLabel="Delete"
        variant="danger"
        isPending={updateStatus.isPending}
        onConfirm={handleConfirmDelete}
        onCancel={() => {
          if (!updateStatus.isPending) {
            setPendingDelete(null);
          }
        }}
      />
    </div>
  );
}
