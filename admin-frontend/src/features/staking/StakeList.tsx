"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { formatTimestamp } from "@/features/finance/lib/format";
import { formatAmount } from "@/lib/format-number";
import { stakeDisplayLabel, stakeStateLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import type { AdminStake } from "./types";

type StakeListProps = {
  stakes: AdminStake[];
  pendingId: number | null;
  onEdit: (stake: AdminStake) => void;
};

function displayClass(status: number): string {
  return status === 1 ? "bg-success/15 text-success" : "bg-surface-elevated text-muted";
}

function stateClass(state: number): string {
  return state === 1 ? "bg-primary/15 text-primary" : "bg-danger/15 text-danger";
}

export function StakeList({ stakes, pendingId, onEdit }: StakeListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>{t("stakeForm.name")}</th>
            <th scope="col" className={thClassName}>{t("page.staking.col.minMax")}</th>
            <th scope="col" className={thClassName}>{t("stakeForm.days")}</th>
            <th scope="col" className={thClassName}>{t("stakeForm.rate")}</th>
            <th scope="col" className={thClassName}>{t("stakeForm.display")}</th>
            <th scope="col" className={thClassName}>{t("stakeForm.state")}</th>
            <th scope="col" className={thClassName}>{t("page.staking.col.added")}</th>
            <th scope="col" className={thClassName}>{t("common.actions")}</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {stakes.map((stake) => {
            const isBusy = pendingId === stake.id;

            return (
              <tr key={stake.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="px-4 py-3 font-medium text-foreground">{stake.name}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">
                  {formatAmount(stake.min)} / {formatAmount(stake.max)}
                </td>
                <td className="px-4 py-3 tabular-nums text-muted">{formatAmount(stake.open)}</td>
                <td className="px-4 py-3 text-foreground">{stake.percent}%</td>
                <td className="px-4 py-3">
                  <span className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${displayClass(stake.status)}`}>
                    {stakeDisplayLabel(t, stake.status)}
                  </span>
                </td>
                <td className="px-4 py-3">
                  <span className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${stateClass(stake.state)}`}>
                    {stakeStateLabel(t, stake.state)}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">{formatTimestamp(stake.addtime)}</td>
                <td className="px-4 py-3">
                  <button
                    type="button"
                    disabled={isBusy}
                    onClick={() => onEdit(stake)}
                    className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                  >
                    {t("common.edit")}
                  </button>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
