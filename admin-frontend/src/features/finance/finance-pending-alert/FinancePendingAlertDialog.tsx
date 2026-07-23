"use client";

import { useEffect, useRef } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import type { FinancePendingAlertSnapshot } from "./useFinancePendingAlert";

type FinancePendingAlertDialogProps = {
  isOpen: boolean;
  alertData: FinancePendingAlertSnapshot | null;
  onDismiss: () => void;
  onViewDeposits: () => void;
  onViewWithdrawals: () => void;
};

export function FinancePendingAlertDialog({
  isOpen,
  alertData,
  onDismiss,
  onViewDeposits,
  onViewWithdrawals,
}: FinancePendingAlertDialogProps) {
  const { t } = useI18n();
  const confirmRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    if (isOpen) {
      confirmRef.current?.focus();
    }
  }, [isOpen]);

  if (!isOpen || !alertData) {
    return null;
  }

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4" role="presentation">
      <div className="absolute inset-0 bg-background/85 backdrop-blur-[1px]" aria-hidden="true" />
      <dialog
        open
        aria-labelledby="finance-pending-alert-title"
        aria-describedby="finance-pending-alert-message"
        className="relative z-10 w-full max-w-md rounded-xl border border-primary/30 bg-surface p-6 shadow-2xl shadow-primary/10"
      >
        <div className="flex items-start gap-3">
          <span className="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-lg font-bold text-primary">
            !
          </span>
          <div className="min-w-0 flex-1">
            <h2 id="finance-pending-alert-title" className="text-lg font-semibold text-foreground">
              {t("alert.financePending.title")}
            </h2>
            <p id="finance-pending-alert-message" className="mt-1 text-sm text-muted">
              {alertData.total === 1
                ? t("alert.financePending.messageOne")
                : t("alert.financePending.messageMany", { count: alertData.total })}
            </p>
            <ul className="mt-3 space-y-1 text-sm text-foreground">
              {alertData.deposits > 0 ? (
                <li>{t("alert.financePending.deposits", { count: alertData.deposits })}</li>
              ) : null}
              {alertData.withdrawals > 0 ? (
                <li>{t("alert.financePending.withdrawals", { count: alertData.withdrawals })}</li>
              ) : null}
            </ul>
          </div>
        </div>

        <div className="mt-6 flex flex-wrap items-center justify-end gap-3">
          {alertData.deposits > 0 ? (
            <button
              type="button"
              onClick={onViewDeposits}
              className="text-sm font-medium text-primary transition hover:opacity-80"
            >
              {t("alert.financePending.viewDeposits")}
            </button>
          ) : null}
          {alertData.withdrawals > 0 ? (
            <button
              type="button"
              onClick={onViewWithdrawals}
              className="text-sm font-medium text-primary transition hover:opacity-80"
            >
              {t("alert.financePending.viewWithdrawals")}
            </button>
          ) : null}
          <button
            ref={confirmRef}
            type="button"
            onClick={onDismiss}
            className="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-[var(--color-on-primary)] transition hover:opacity-90"
          >
            {t("alert.financePending.confirm")}
          </button>
        </div>
      </dialog>
    </div>
  );
}
