"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import {
  depositMethodLabel,
  depositStatusLabel,
} from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatAmount } from "@/lib/format-number";
import { depositStatusClass, formatTimestamp } from "../lib/format";
import type { AdminDeposit } from "./types";

type DepositListProps = {
  deposits: AdminDeposit[];
  pendingActionId: number | null;
  onApprove: (deposit: AdminDeposit) => void;
  onReject: (deposit: AdminDeposit) => void;
  onDelete: (deposit: AdminDeposit) => void;
};

export function DepositList({
  deposits,
  pendingActionId,
  onApprove,
  onReject,
  onDelete,
}: DepositListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>{t("common.username")}</th>
            <th scope="col" className={thClassName}>{t("common.coin")}</th>
            <th scope="col" className={thClassName}>{t("common.amount")}</th>
            <th scope="col" className={thClassName}>{t("common.method")}</th>
            <th scope="col" className={thClassName}>{t("common.status")}</th>
            <th scope="col" className={thClassName}>{t("common.submitted")}</th>
            <th scope="col" className={thClassName}>{t("common.actions")}</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {deposits.map((deposit) => {
            const isPending = deposit.status === 1;
            const isBusy = pendingActionId === deposit.id;

            return (
              <tr key={deposit.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="break-all px-4 py-3 font-medium text-foreground">{deposit.username}</td>
                <td className="px-4 py-3 uppercase text-foreground">{deposit.coin}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">{formatAmount(deposit.num)}</td>
                <td className="px-4 py-3 text-muted">{depositMethodLabel(t, deposit.method)}</td>
                <td className="px-4 py-3">
                  <span
                    className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${depositStatusClass(deposit.status)}`}
                  >
                    {depositStatusLabel(t, deposit.status)}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">{formatTimestamp(deposit.addtime)}</td>
                <td className="px-4 py-3">
                  <div className="flex flex-wrap gap-2">
                    {isPending ? (
                      <>
                        <button type="button" disabled={isBusy} onClick={() => onApprove(deposit)} className="rounded bg-primary px-2.5 py-1 text-xs font-medium text-background transition hover:opacity-90 disabled:opacity-40">{t("action.approve")}</button>
                        <button type="button" disabled={isBusy} onClick={() => onReject(deposit)} className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40">{t("action.reject")}</button>
                      </>
                    ) : null}
                    <button type="button" disabled={isBusy} onClick={() => onDelete(deposit)} className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40">{t("common.delete")}</button>
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
