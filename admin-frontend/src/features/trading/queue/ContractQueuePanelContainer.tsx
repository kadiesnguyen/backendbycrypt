"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useState } from "react";
import { ContractQueuePanel } from "./ContractQueuePanel";
import { ContractQueuePanelSkeleton } from "./ContractQueuePanelSkeleton";
import { useContractQueue } from "./useContractQueue";
import { useContractQueueActions } from "./useContractQueueActions";
import type { QueueAction, QueueEntry } from "./types";

type ContractQueuePanelContainerProps = {
  embedded?: boolean;
  part?: "all" | "actions" | "table";
};

export function ContractQueuePanelContainer({
  embedded = false,
  part = "all",
}: ContractQueuePanelContainerProps) {
  const { t } = useI18n();
  const [pendingAction, setPendingAction] = useState<QueueAction | null>(null);
  const [pendingDelete, setPendingDelete] = useState<QueueEntry | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);

  const { data, isLoading, isError, error, refetch, isFetching } = useContractQueue();
  const { action, updateEntry, deleteEntry } = useContractQueueActions();

  const dialogMeta = pendingAction
    ? {
        next_win: {
          title: t("page.queue.confirm.nextWinTitle"),
          message: t("page.queue.confirm.nextWinMessage"),
          variant: "default" as const,
        },
        next_loss: {
          title: t("page.queue.confirm.nextLossTitle"),
          message: t("page.queue.confirm.nextLossMessage"),
          variant: "danger" as const,
        },
      }[pendingAction]
    : null;

  const handleConfirm = async () => {
    if (!pendingAction) return;

    setActionError(null);
    try {
      await action.mutateAsync(pendingAction);
      setPendingAction(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const handleUpdate = async (id: number, result: "WIN" | "LOSS") => {
    setActionError(null);
    try {
      await updateEntry.mutateAsync({ id, payload: { result } });
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const handleDeleteConfirm = async () => {
    if (!pendingDelete) return;

    setActionError(null);
    try {
      await deleteEntry.mutateAsync(pendingDelete.id);
      setPendingDelete(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const entries = data?.data ?? [];

  const rowPendingId =
    updateEntry.isPending && updateEntry.variables
      ? updateEntry.variables.id
      : deleteEntry.isPending && deleteEntry.variables !== undefined
        ? deleteEntry.variables
        : null;

  const showHeader = part === "all" || part === "table";
  const showActions = part === "all" || part === "actions";
  const showTable = part === "all" || part === "table";

  return (
    <div className={embedded ? "space-y-4" : "space-y-6"}>
      {showHeader ? (
        embedded ? (
          <div>
            <h2 className="text-lg font-semibold text-foreground">{t("page.queue.title")}</h2>
            <p className="mt-1 text-sm text-muted">{t("page.queue.description")}</p>
          </div>
        ) : (
          <PageHeader titleKey="page.queue.title" descriptionKey="page.queue.description" />
        )
      ) : null}

      {showActions ? (
        <section
          aria-label={t("page.queue.actions")}
          className="rounded-lg border border-border bg-surface p-4"
        >
          <h2 className="text-sm font-medium text-foreground">{t("page.queue.actions")}</h2>
          <p className="mt-1 text-xs text-muted">{t("page.queue.actionsHint")}</p>
          <div className="mt-4 flex flex-wrap gap-2">
            <button
              type="button"
              disabled={action.isPending}
              onClick={() => {
                setActionError(null);
                setPendingAction("next_win");
              }}
              className="rounded bg-success px-3 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
            >
              {t("page.queue.nextWin")}
            </button>
            <button
              type="button"
              disabled={action.isPending}
              onClick={() => {
                setActionError(null);
                setPendingAction("next_loss");
              }}
              className="rounded bg-danger px-3 py-2 text-sm font-medium text-foreground transition hover:opacity-90 disabled:opacity-40"
            >
              {t("page.queue.nextLoss")}
            </button>
          </div>
        </section>
      ) : null}

      {showActions && actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {showTable && actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {showTable && isLoading ? <ContractQueuePanelSkeleton /> : null}

      {showTable && isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.queue.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {showTable && !isLoading && !isError ? (
        <>
          <div className="flex items-center justify-between text-sm text-muted">
            <p>{t("page.queue.entryCount", { count: String(entries.length) })}</p>
            {isFetching ? <span className="text-primary">{t("common.updating")}</span> : null}
          </div>

          {entries.length === 0 ? (
            <div role="status" className="rounded-lg border border-border bg-surface px-6 py-12 text-center">
              <h2 className="text-sm font-medium text-foreground">{t("page.queue.emptyTitle")}</h2>
              <p className="mt-1 text-sm text-muted">{t("page.queue.emptyHint")}</p>
            </div>
          ) : (
            <ContractQueuePanel
              entries={entries}
              pendingId={rowPendingId}
              onUpdate={handleUpdate}
              onDelete={setPendingDelete}
            />
          )}
        </>
      ) : null}

      {showActions ? (
        <ConfirmDialog
          isOpen={pendingAction !== null}
          title={dialogMeta?.title ?? t("common.confirm")}
          message={dialogMeta?.message ?? ""}
          confirmLabel={t("common.confirm")}
          variant={dialogMeta?.variant ?? "default"}
          isPending={action.isPending}
          onConfirm={handleConfirm}
          onCancel={() => {
            if (!action.isPending) {
              setPendingAction(null);
            }
          }}
        />
      ) : null}

      {showTable ? (
        <ConfirmDialog
          isOpen={pendingDelete !== null}
          title={t("page.queue.confirm.deleteTitle")}
          message={
            pendingDelete
              ? t("page.queue.confirm.deleteMessage", {
                  round: String(pendingDelete.round_no),
                  result: pendingDelete.result.toUpperCase() === "LOSS" ? t("action.loss") : t("action.win"),
                })
              : ""
          }
          confirmLabel={t("common.delete")}
          variant="danger"
          isPending={deleteEntry.isPending}
          onConfirm={handleDeleteConfirm}
          onCancel={() => {
            if (!deleteEntry.isPending) {
              setPendingDelete(null);
            }
          }}
        />
      ) : null}
    </div>
  );
}
