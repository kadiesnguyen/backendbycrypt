"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";

import { useState } from "react";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { DepositPortFormDialog } from "./DepositPortFormDialog";
import { DepositPortList } from "./DepositPortList";
import { DepositPortListSkeleton } from "./DepositPortListSkeleton";
import { useDepositPort, useDepositPortFormMeta, useDepositPorts } from "./useDepositPorts";
import { useDepositPortActions } from "./useDepositPortActions";
import type { AdminDepositPort, DepositPortUpsertPayload } from "./types";

export function DepositPortListContainer() {
  const { t } = useI18n();
  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [pendingDelete, setPendingDelete] = useState<AdminDepositPort | null>(null);
  const [formError, setFormError] = useState<string | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);
  const [pendingActionId, setPendingActionId] = useState<number | null>(null);

  const { data, isLoading, isError, error, refetch, isFetching } = useDepositPorts();
  const { data: detailData, isLoading: isLoadingDetail } = useDepositPort(formOpen ? editingId : null);
  const { data: formMetaData } = useDepositPortFormMeta();
  const { create, update, updateStatus } = useDepositPortActions();

  const handleOpenCreate = () => {
    setFormError(null);
    setEditingId(null);
    setFormOpen(true);
  };

  const handleOpenEdit = (item: AdminDepositPort) => {
    setFormError(null);
    setEditingId(item.id);
    setFormOpen(true);
  };

  const handleFormSubmit = async (payload: DepositPortUpsertPayload) => {
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

  const handleToggleStatus = async (item: AdminDepositPort) => {
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
      await updateStatus.mutateAsync({ ids: [pendingDelete.id], type: "delete" });
      setPendingDelete(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : "Delete failed.");
    } finally {
      setPendingActionId(null);
    }
  };

  const items = data?.data ?? [];
  const editingItem = editingId ? (detailData?.data ?? null) : null;
  const formMeta = detailData?.meta ?? formMetaData?.data;
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
      <PageHeader titleKey="page.depositPorts.title" descriptionKey="page.depositPorts.description" action={headerAction} />

      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <DepositPortListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : "Failed to load deposit ports."}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && items.length === 0 ? (
        <div role="status" className="rounded-lg border border-border bg-surface px-6 py-12 text-center">
          <h2 className="text-sm font-medium text-foreground">No deposit ports configured</h2>
          <p className="mt-1 text-sm text-muted">Add a deposit port to enable user deposits.</p>
          <button
            type="button"
            onClick={handleOpenCreate}
            className="mt-4 rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
          >
            Add port
          </button>
        </div>
      ) : null}

      {!isLoading && !isError && items.length > 0 ? (
        <>
          {isFetching ? (
            <p className="text-sm text-muted">
              <span className="text-primary">Updating…</span>
            </p>
          ) : null}
          <DepositPortList
            items={items}
            onEdit={handleOpenEdit}
            onToggleStatus={handleToggleStatus}
            onDelete={(item) => {
              setActionError(null);
              setPendingDelete(item);
            }}
            pendingId={pendingActionId}
          />
        </>
      ) : null}

      <DepositPortFormDialog
        isOpen={formOpen}
        editingItem={editingItem}
        formMeta={formMeta}
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
        title="Delete deposit port"
        message={
          pendingDelete
            ? `Delete "${pendingDelete.name}"? This action cannot be undone.`
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
