"use client";

import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { useI18n } from "@/lib/i18n/useI18n";
import { useState } from "react";
import type { QueueAction } from "./types";
import { contractOpsButtonClass } from "./contract-ops-styles";
import { useContractQueueActions } from "./useContractQueueActions";

export function ContractQueueQuickActions() {
  const { t } = useI18n();
  const [pendingAction, setPendingAction] = useState<QueueAction | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);
  const { action } = useContractQueueActions();

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

  return (
    <>
      <div className="flex flex-wrap items-center gap-2">
        <button
          type="button"
          disabled={action.isPending}
          onClick={() => {
            setActionError(null);
            setPendingAction("next_win");
          }}
          className={`${contractOpsButtonClass} bg-success text-background`}
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
          className={`${contractOpsButtonClass} bg-danger text-foreground`}
        >
          {t("page.queue.nextLoss")}
        </button>
      </div>

      {actionError ? (
        <p role="alert" className="w-full text-xs text-danger">
          {actionError}
        </p>
      ) : null}

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
    </>
  );
}
