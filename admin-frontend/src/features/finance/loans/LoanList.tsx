"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import type { AdminLoan } from "./types";

function statusClass(status: string): string {
  switch (status) {
    case "pending":
      return "bg-primary/15 text-primary";
    case "active":
      return "bg-primary/20 text-primary";
    case "repaid":
      return "bg-primary/10 text-primary";
    case "rejected":
      return "bg-danger/15 text-danger";
    case "overdue":
      return "bg-danger/20 text-danger";
    default:
      return "bg-surface-elevated text-muted";
  }
}

type LoanListProps = {
  loans: AdminLoan[];
  pendingActionId: number | null;
  onApprove: (loan: AdminLoan) => void;
  onReject: (loan: AdminLoan) => void;
  onViewImages: (loan: AdminLoan) => void;
};

export function LoanList({
  loans,
  pendingActionId,
  onApprove,
  onReject,
  onViewImages,
}: LoanListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>{t("common.username")}</th>
            <th scope="col" className={thClassName}>{t("common.amount")}</th>
            <th scope="col" className={thClassName}>{t("page.loans.interest")}</th>
            <th scope="col" className={thClassName}>{t("page.loans.repay")}</th>
            <th scope="col" className={thClassName}>{t("common.status")}</th>
            <th scope="col" className={thClassName}>{t("common.submitted")}</th>
            <th scope="col" className={thClassName}>{t("common.actions")}</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {loans.map((loan) => {
            const isPending = loan.status === "pending";
            const isBusy = pendingActionId === loan.id;

            return (
              <tr key={loan.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="break-all px-4 py-3 font-medium text-foreground">{loan.username}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">
                  {formatAmount(loan.amount)} {loan.currency}
                </td>
                <td className="px-4 py-3 tabular-nums text-muted">{formatAmount(loan.interest_amount)}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">{formatAmount(loan.repay_amount)}</td>
                <td className="px-4 py-3">
                  <span className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${statusClass(loan.status)}`}>
                    {loan.status_label}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">{loan.created_at ?? "—"}</td>
                <td className="px-4 py-3">
                  <div className="flex flex-wrap gap-2">
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onViewImages(loan)}
                      className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                    >
                      {t("page.loans.viewImages")}
                    </button>
                    {isPending ? (
                      <>
                        <button
                          type="button"
                          disabled={isBusy}
                          onClick={() => onApprove(loan)}
                          className="rounded bg-primary px-2.5 py-1 text-xs font-medium text-background transition hover:opacity-90 disabled:opacity-40"
                        >
                          {t("action.approve")}
                        </button>
                        <button
                          type="button"
                          disabled={isBusy}
                          onClick={() => onReject(loan)}
                          className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40"
                        >
                          {t("action.reject")}
                        </button>
                      </>
                    ) : null}
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
