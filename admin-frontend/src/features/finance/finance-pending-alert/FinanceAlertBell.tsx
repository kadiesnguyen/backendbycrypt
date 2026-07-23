"use client";

import Link from "next/link";
import { useEffect, useRef } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import { useFinancePendingAlertContext } from "./FinancePendingAlertContext";

function formatBadgeCount(count: number): string {
  return count > 99 ? "99+" : String(count);
}

function BellIcon({ ringing }: { ringing: boolean }) {
  return (
    <svg
      aria-hidden="true"
      className={`h-5 w-5 ${ringing ? "animate-pulse" : ""}`}
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
      strokeWidth={1.8}
    >
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9"
      />
    </svg>
  );
}

export function FinanceAlertBell() {
  const { t } = useI18n();
  const {
    panelOpen,
    setPanelOpen,
    deposits,
    withdrawals,
    total,
    unread,
    markAsRead,
  } = useFinancePendingAlertContext();
  const rootRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!panelOpen) {
      return;
    }

    const onPointerDown = (event: MouseEvent) => {
      if (!rootRef.current?.contains(event.target as Node)) {
        setPanelOpen(false);
      }
    };

    document.addEventListener("mousedown", onPointerDown);
    return () => document.removeEventListener("mousedown", onPointerDown);
  }, [panelOpen, setPanelOpen]);

  return (
    <div ref={rootRef} className="relative">
      <button
        type="button"
        onClick={() => setPanelOpen((open) => !open)}
        aria-expanded={panelOpen}
        aria-label={t("alert.financePending.bellLabel")}
        className="relative inline-flex h-9 w-9 items-center justify-center rounded border border-border text-foreground transition hover:border-primary hover:text-primary"
      >
        <BellIcon ringing={unread > 0} />
        {unread > 0 ? (
          <span className="absolute -right-1 -top-1 inline-flex min-w-[1.125rem] items-center justify-center rounded-full bg-danger px-1 text-[10px] font-semibold leading-4 text-white">
            {formatBadgeCount(unread)}
          </span>
        ) : null}
      </button>

      {panelOpen ? (
        <div
          role="dialog"
          aria-label={t("alert.financePending.panelTitle")}
          className="absolute right-0 z-50 mt-2 w-72 rounded-lg border border-border bg-surface p-3 shadow-xl"
        >
          <div className="flex items-start justify-between gap-2">
            <div className="min-w-0">
              <p className="text-sm font-semibold text-foreground">
                {t("alert.financePending.panelTitle")}
              </p>
              <p className="mt-1 text-xs text-muted">
                {unread > 0
                  ? t("alert.financePending.panelUnreadHint", { count: unread })
                  : total > 0
                    ? t("alert.financePending.panelHint", { count: total })
                    : t("alert.financePending.panelEmpty")}
              </p>
            </div>
            {unread > 0 ? (
              <button
                type="button"
                onClick={markAsRead}
                className="shrink-0 whitespace-nowrap text-xs font-medium text-primary transition hover:opacity-80"
              >
                {t("alert.financePending.markRead")}
              </button>
            ) : null}
          </div>
          <ul className="mt-3 space-y-2">
            <li>
              <Link
                href="/finance/deposits"
                onClick={() => setPanelOpen(false)}
                className="flex items-center justify-between rounded border border-border px-3 py-2 text-sm text-foreground transition hover:border-primary hover:text-primary"
              >
                <span>{t("nav.deposits")}</span>
                <span className="tabular-nums font-semibold text-danger">{deposits}</span>
              </Link>
            </li>
            <li>
              <Link
                href="/finance/withdrawals"
                onClick={() => setPanelOpen(false)}
                className="flex items-center justify-between rounded border border-border px-3 py-2 text-sm text-foreground transition hover:border-primary hover:text-primary"
              >
                <span>{t("nav.withdrawals")}</span>
                <span className="tabular-nums font-semibold text-danger">{withdrawals}</span>
              </Link>
            </li>
          </ul>
        </div>
      ) : null}
    </div>
  );
}
