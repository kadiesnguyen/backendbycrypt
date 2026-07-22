"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
  UsernameFilter,
} from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import Link from "next/link";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { useCallback, useMemo, useState } from "react";
import { LoanList } from "./LoanList";
import { LoanListSkeleton } from "./LoanListSkeleton";
import { useLoanActions } from "./useLoanActions";
import { useLoans } from "./useLoans";
import type { AdminLoan } from "./types";

type PendingAction = {
  loan: AdminLoan;
  type: "approve" | "reject";
};

export function LoanListContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");
  const username = searchParams.get("username") ?? "";
  const status = searchParams.get("status") ?? "";

  const [usernameInput, setUsernameInput] = useState(username);
  const [pendingAction, setPendingAction] = useState<PendingAction | null>(null);
  const [note, setNote] = useState("");
  const [actionError, setActionError] = useState<string | null>(null);
  const [imagesLoan, setImagesLoan] = useState<AdminLoan | null>(null);

  const queryParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 15,
      username: username || undefined,
      status: status || undefined,
    }),
    [page, username, status],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = useLoans(queryParams);
  const { approve, reject } = useLoanActions();

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

  const handleConfirm = async () => {
    if (!pendingAction) return;
    setActionError(null);
    const { loan, type } = pendingAction;
    const trimmed = note.trim() || undefined;

    try {
      if (type === "approve") {
        await approve.mutateAsync({ id: loan.id, note: trimmed });
      } else {
        await reject.mutateAsync({ id: loan.id, note: trimmed });
      }
      setPendingAction(null);
      setNote("");
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const loans = data?.data ?? [];
  const meta = data?.meta;
  const isActionPending = approve.isPending || reject.isPending;
  const pendingActionId = isActionPending && pendingAction ? pendingAction.loan.id : null;

  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <PageHeader titleKey="page.loans.title" descriptionKey="page.loans.description" />
        <Link
          href="/finance/loan-settings"
          className="rounded border border-border px-3 py-2 text-sm font-medium text-foreground transition hover:bg-surface-elevated"
        >
          {t("page.loans.settingsLink")}
        </Link>
      </div>

      <UsernameFilter
        value={usernameInput}
        onChange={setUsernameInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ username: usernameInput.trim() || null, page: "1" });
        }}
      />

      <div className="flex flex-wrap gap-2">
        {[
          { value: "", label: t("page.loans.filter.all") },
          { value: "pending", label: t("page.loans.status.pending") },
          { value: "active", label: t("page.loans.status.active") },
          { value: "overdue", label: t("page.loans.status.overdue") },
          { value: "rejected", label: t("page.loans.status.rejected") },
          { value: "repaid", label: t("page.loans.status.repaid") },
        ].map((item) => (
          <button
            key={item.value || "all"}
            type="button"
            onClick={() => updateParams({ status: item.value || null, page: "1" })}
            className={`rounded-full px-3 py-1 text-xs font-medium transition ${
              status === item.value
                ? "bg-primary text-background"
                : "border border-border text-muted hover:bg-surface-elevated"
            }`}
          >
            {item.label}
          </button>
        ))}
      </div>

      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <LoanListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.loans.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && loans.length === 0 ? (
        <EmptyState titleKey="page.loans.noResults" descriptionKey="common.noResultsHint" />
      ) : null}

      {!isLoading && !isError && loans.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <LoanList
            loans={loans}
            pendingActionId={pendingActionId}
            onApprove={(loan) => {
              setActionError(null);
              setNote("");
              setPendingAction({ loan, type: "approve" });
            }}
            onReject={(loan) => {
              setActionError(null);
              setNote("");
              setPendingAction({ loan, type: "reject" });
            }}
            onViewImages={setImagesLoan}
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

      {pendingAction ? (
        <div className="fixed inset-x-0 bottom-0 z-40 border-t border-border bg-surface p-4 shadow-lg sm:static sm:border sm:rounded-lg sm:shadow-none">
          <label className="mb-2 block text-sm font-medium text-foreground" htmlFor="loan-note">
            {t("page.loans.note")}
          </label>
          <textarea
            id="loan-note"
            value={note}
            onChange={(e) => setNote(e.target.value)}
            rows={2}
            className="mb-3 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
            placeholder={t("page.loans.notePlaceholder")}
          />
        </div>
      ) : null}

      <ConfirmDialog
        isOpen={pendingAction !== null}
        title={
          pendingAction?.type === "approve"
            ? t("page.loans.confirm.approveTitle")
            : t("page.loans.confirm.rejectTitle")
        }
        message={
          pendingAction
            ? pendingAction.type === "approve"
              ? t("page.loans.confirm.approveMessage", {
                  id: String(pendingAction.loan.id),
                  username: pendingAction.loan.username,
                  amount: formatAmount(pendingAction.loan.amount),
                })
              : t("page.loans.confirm.rejectMessage", {
                  id: String(pendingAction.loan.id),
                  username: pendingAction.loan.username,
                })
            : ""
        }
        confirmLabel={pendingAction?.type === "approve" ? t("action.approve") : t("action.reject")}
        variant={pendingAction?.type === "reject" ? "danger" : "default"}
        isPending={isActionPending}
        onConfirm={handleConfirm}
        onCancel={() => {
          if (!isActionPending) {
            setPendingAction(null);
            setNote("");
          }
        }}
      />

      {imagesLoan ? (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
          role="dialog"
          aria-modal="true"
          aria-label={t("page.loans.viewImages")}
          onClick={() => setImagesLoan(null)}
        >
          <div
            className="max-h-[90vh] w-full max-w-3xl space-y-4 overflow-auto rounded-lg bg-surface p-4"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-center justify-between gap-3">
              <h2 className="text-base font-semibold text-foreground">
                {t("page.loans.viewImages")} #{imagesLoan.id}
              </h2>
              <button
                type="button"
                onClick={() => setImagesLoan(null)}
                className="rounded border border-border px-2.5 py-1 text-xs text-foreground"
              >
                {t("common.close")}
              </button>
            </div>
            <div className="grid gap-4 sm:grid-cols-2">
              {imagesLoan.img_front ? (
                // eslint-disable-next-line @next/next/no-img-element
                <img src={imagesLoan.img_front} alt="front" className="w-full rounded border border-border object-contain" />
              ) : (
                <p className="text-sm text-muted">{t("page.loans.noImage")}</p>
              )}
              {imagesLoan.img_back ? (
                // eslint-disable-next-line @next/next/no-img-element
                <img src={imagesLoan.img_back} alt="back" className="w-full rounded border border-border object-contain" />
              ) : (
                <p className="text-sm text-muted">{t("page.loans.noImage")}</p>
              )}
            </div>
            {imagesLoan.note ? (
              <p className="text-sm text-muted">
                {t("page.loans.note")}: {imagesLoan.note}
              </p>
            ) : null}
          </div>
        </div>
      ) : null}
    </div>
  );
}
