"use client";

import { contractHyzdLabel } from "@/lib/i18n/entity-labels";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useEffect, useRef } from "react";
import type { ContractOrderAlertData } from "./types";

type ContractOrderAlertDialogProps = {
  isOpen: boolean;
  alertData: ContractOrderAlertData | null;
  isDismissing: boolean;
  onDismiss: () => void;
  onViewOrders: () => void;
};

export function ContractOrderAlertDialog({
  isOpen,
  alertData,
  isDismissing,
  onDismiss,
  onViewOrders,
}: ContractOrderAlertDialogProps) {
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

  const count = alertData.count;

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4" role="presentation">
      <div className="absolute inset-0 bg-background/85 backdrop-blur-[1px]" aria-hidden="true" />
      <dialog
        open
        aria-labelledby="contract-order-alert-title"
        aria-describedby="contract-order-alert-message"
        className="relative z-10 w-full max-w-lg rounded-xl border border-primary/30 bg-surface p-6 shadow-2xl shadow-primary/10"
      >
        <div className="flex items-start gap-3">
          <span className="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/15 text-lg font-bold text-primary">
            !
          </span>
          <div className="min-w-0 flex-1">
            <h2 id="contract-order-alert-title" className="text-lg font-semibold text-foreground">
              {t("alert.contractOrder.title")}
            </h2>
            <p id="contract-order-alert-message" className="mt-1 text-sm text-muted">
              {count === 1
                ? t("alert.contractOrder.messageOne")
                : t("alert.contractOrder.messageMany", { count })}
            </p>
          </div>
        </div>

        {alertData.orders.length > 0 ? (
          <ul className="mt-4 max-h-56 space-y-2 overflow-y-auto rounded-lg border border-border/70 bg-surface-elevated/40 p-2">
            {alertData.orders.map((order) => (
              <li
                key={order.id}
                className="flex items-center justify-between gap-3 rounded-md px-3 py-2 text-sm"
              >
                <div className="min-w-0">
                  <p className="break-all font-medium text-foreground">{order.username}</p>
                  <p className="text-xs text-muted">
                    #{order.id} · {order.coinname} · {contractHyzdLabel(t, order.hyzd)}
                  </p>
                </div>
                <span className="shrink-0 font-semibold tabular-nums text-primary">{formatAmount(order.num)}</span>
              </li>
            ))}
          </ul>
        ) : null}

        {count > alertData.orders.length ? (
          <p className="mt-2 text-xs text-muted">
            {t("alert.contractOrder.moreOrders", { count: count - alertData.orders.length })}
          </p>
        ) : null}

        <div className="mt-6 flex flex-wrap items-center justify-end gap-3">
          <button
            type="button"
            disabled={isDismissing}
            onClick={onViewOrders}
            className="text-sm font-medium text-primary transition hover:opacity-80 disabled:opacity-50"
          >
            {t("alert.contractOrder.viewOrders")}
          </button>
          <button
            ref={confirmRef}
            type="button"
            disabled={isDismissing}
            onClick={onDismiss}
            className="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-[var(--color-on-primary)] transition hover:opacity-90 disabled:opacity-50"
          >
            {isDismissing ? t("common.updating") : t("alert.contractOrder.confirm")}
          </button>
        </div>
      </dialog>
    </div>
  );
}
