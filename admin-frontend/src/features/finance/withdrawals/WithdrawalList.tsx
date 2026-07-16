"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { withdrawalStatusLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatAmount } from "@/lib/format-number";
import { formatTimestamp, withdrawalStatusClass } from "../lib/format";
import type { AdminWithdrawal } from "./types";

type WithdrawalListProps = {
  withdrawals: AdminWithdrawal[];
  pendingActionId: number | null;
  onApprove: (withdrawal: AdminWithdrawal) => void;
  onReject: (withdrawal: AdminWithdrawal) => void;
  onDelete: (withdrawal: AdminWithdrawal) => void;
};

export function WithdrawalList({
  withdrawals,
  pendingActionId,
  onApprove,
  onReject,
  onDelete,
}: WithdrawalListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>{t("common.username")}</th>
            <th scope="col" className={thClassName}>{t("common.coin")}</th>
            <th scope="col" className={thClassName}>{t("common.amount")}</th>
            <th scope="col" className={thClassName}>{t("common.net")}</th>
            <th scope="col" className={thClassName}>{t("common.method")}</th>
            <th scope="col" className={thClassName}>{t("common.status")}</th>
            <th scope="col" className={thClassName}>{t("common.submitted")}</th>
            <th scope="col" className={thClassName}>{t("common.actions")}</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {withdrawals.map((withdrawal) => {
            const isPending = withdrawal.status === 1;
            const isBusy = pendingActionId === withdrawal.id;

            return (
              <tr key={withdrawal.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="break-all px-4 py-3 font-medium text-foreground">{withdrawal.username}</td>
                <td className="px-4 py-3 uppercase text-foreground">{withdrawal.coinname}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">{formatAmount(withdrawal.num)}</td>
                <td className="px-4 py-3 tabular-nums text-muted">{formatAmount(withdrawal.mum)}</td>
                <td className="px-4 py-3 text-muted">{withdrawal.method_label}</td>
                <td className="px-4 py-3">
                  <span
                    className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${withdrawalStatusClass(withdrawal.status)}`}
                  >
                    {withdrawalStatusLabel(t, withdrawal.status)}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">{formatTimestamp(withdrawal.addtime)}</td>
                <td className="px-4 py-3">
                  <div className="flex flex-wrap gap-2">
                    {isPending ? (
                      <>
                        <button type="button" disabled={isBusy} onClick={() => onApprove(withdrawal)} className="rounded bg-primary px-2.5 py-1 text-xs font-medium text-background transition hover:opacity-90 disabled:opacity-40">{t("action.approve")}</button>
                        <button type="button" disabled={isBusy} onClick={() => onReject(withdrawal)} className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40">{t("action.reject")}</button>
                      </>
                    ) : null}
                    <button type="button" disabled={isBusy} onClick={() => onDelete(withdrawal)} className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40">{t("common.delete")}</button>
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
