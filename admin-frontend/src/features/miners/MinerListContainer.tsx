"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
} from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { MinerFormDialog } from "./MinerFormDialog";
import { MinerList } from "./MinerList";
import { MinerListSkeleton } from "./MinerListSkeleton";
import { useMiner, useMinerFormMeta, useMiners } from "./useMiners";
import { useMinerActions } from "./useMinerActions";
import type { AdminMiner, MinerUpsertPayload } from "./types";

export function MinerListContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");

  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [pendingDelete, setPendingDelete] = useState<AdminMiner | null>(null);
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

  const { data, isLoading, isError, error, refetch, isFetching } = useMiners(queryParams);
  const { data: detailData, isLoading: isLoadingDetail } = useMiner(formOpen ? editingId : null);
  const { data: formMetaData } = useMinerFormMeta(formOpen);
  const { create, update, updateStatus } = useMinerActions();

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

  const handleOpenEdit = (miner: AdminMiner) => {
    setFormError(null);
    setEditingId(miner.id);
    setFormOpen(true);
  };

  const handleFormSubmit = async (payload: MinerUpsertPayload) => {
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
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
    }
  };

  const handleToggleStatus = async (miner: AdminMiner) => {
    setActionError(null);
    setPendingActionId(miner.id);
    try {
      await updateStatus.mutateAsync({
        ids: [miner.id],
        type: miner.status === 1 ? "2" : "1",
      });
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    } finally {
      setPendingActionId(null);
    }
  };

  const handleConfirmDelete = async () => {
    if (!pendingDelete) return;
    setActionError(null);
    setPendingActionId(pendingDelete.id);
    try {
      await updateStatus.mutateAsync({ ids: [pendingDelete.id], type: "3" });
      setPendingDelete(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    } finally {
      setPendingActionId(null);
    }
  };

  const miners = data?.data ?? [];
  const meta = data?.meta;
  const editingItem = editingId ? (detailData?.data ?? null) : null;
  const coins = formMetaData?.data.coins ?? detailData?.meta?.coins ?? [];
  const isFormPending = create.isPending || update.isPending;

  const headerAction = (
    <button
      type="button"
      onClick={handleOpenCreate}
      className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
    >
      {t("action.add")}
    </button>
  );

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.miners.title" descriptionKey="page.miners.description" action={headerAction} />

      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <MinerListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.miners.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && miners.length === 0 ? (
        <EmptyState
          titleKey="page.miners.noResults"
          descriptionKey="page.miners.noResultsHint"
          action={
            <button
              type="button"
              onClick={handleOpenCreate}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
            >
              {t("action.add")}
            </button>
          }
        />
      ) : null}

      {!isLoading && !isError && miners.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <MinerList
            miners={miners}
            pendingId={pendingActionId}
            onEdit={handleOpenEdit}
            onToggleStatus={handleToggleStatus}
            onDelete={(miner) => {
              setActionError(null);
              setPendingDelete(miner);
            }}
          />
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}

      <MinerFormDialog
        isOpen={formOpen}
        editingItem={editingItem}
        coins={coins}
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
        title={t("page.miners.deleteTitle")}
        message={
          pendingDelete
            ? t("page.miners.deleteMessage", { title: pendingDelete.title })
            : ""
        }
        confirmLabel={t("common.delete")}
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
