"use client";

import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useEffect, useRef } from "react";
import type { PerpPositionAlertData } from "./types";

type Props = {
  isOpen: boolean;
  alertData: PerpPositionAlertData | null;
  isDismissing: boolean;
  onDismiss: () => void;
  onViewPositions: () => void;
};

export function PerpPositionAlertDialog({
  isOpen,
  alertData,
  isDismissing,
  onDismiss,
  onViewPositions,
}: Props) {
  const { t } = useI18n();
  const confirmRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    if (isOpen) confirmRef.current?.focus();
  }, [isOpen]);

  if (!isOpen || !alertData) return null;

  const count = alertData.count;

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4" role="presentation">
      <div className="absolute inset-0 bg-background/85 backdrop-blur-[1px]" aria-hidden="true" />
      <dialog
        open
        aria-labelledby="perp-alert-title"
        className="relative z-10 w-full max-w-lg rounded-xl border border-primary/30 bg-surface p-6 shadow-2xl shadow-primary/10"
      >
        <h2 id="perp-alert-title" className="text-lg font-semibold text-foreground">
          {t("alert.perpPosition.title")}
        </h2>
        <p className="mt-1 text-sm text-muted">
          {count === 1
            ? t("alert.perpPosition.messageOne")
            : t("alert.perpPosition.messageMany", { count })}
        </p>
        {alertData.positions.length > 0 ? (
          <ul className="mt-4 max-h-56 space-y-2 overflow-y-auto rounded-lg border border-border/70 bg-surface-elevated/40 p-2">
            {alertData.positions.map((p) => (
              <li key={p.id} className="flex items-center justify-between gap-3 rounded-md px-3 py-2 text-sm">
                <div className="min-w-0">
                  <p className="break-all font-medium text-foreground">{p.username}</p>
                  <p className="text-xs text-muted">
                    #{p.id} · {p.symbol} · {p.leverage}x · {p.side}
                  </p>
                </div>
                <span className="shrink-0 font-semibold tabular-nums text-primary">
                  {formatAmount(p.margin)}
                </span>
              </li>
            ))}
          </ul>
        ) : null}
        <div className="mt-6 flex flex-wrap items-center justify-end gap-3">
          <button
            type="button"
            disabled={isDismissing}
            onClick={onViewPositions}
            className="text-sm font-medium text-primary transition hover:opacity-80 disabled:opacity-50"
          >
            {t("alert.perpPosition.viewPositions")}
          </button>
          <button
            ref={confirmRef}
            type="button"
            disabled={isDismissing}
            onClick={onDismiss}
            className="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-[var(--color-on-primary)] disabled:opacity-50"
          >
            {isDismissing ? t("common.updating") : t("alert.perpPosition.confirm")}
          </button>
        </div>
      </dialog>
    </div>
  );
}
