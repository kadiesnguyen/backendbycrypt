"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { formatTimestamp } from "@/features/finance/lib/format";
import { formatAmount } from "@/lib/format-number";
import { minerStatusLabel, minerTypeLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import type { AdminMiner } from "./types";

type MinerListProps = {
  miners: AdminMiner[];
  pendingId: number | null;
  onEdit: (miner: AdminMiner) => void;
  onToggleStatus: (miner: AdminMiner) => void;
  onDelete: (miner: AdminMiner) => void;
};

function statusClass(status: number): string {
  return status === 1 ? "bg-success/15 text-success" : "bg-danger/15 text-danger";
}

export function MinerList({ miners, pendingId, onEdit, onToggleStatus, onDelete }: MinerListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>{t("common.title")}</th>
            <th scope="col" className={thClassName}>{t("common.type")}</th>
            <th scope="col" className={thClassName}>{t("common.price")}</th>
            <th scope="col" className={thClassName}>{t("page.miners.col.output")}</th>
            <th scope="col" className={thClassName}>{t("page.miners.col.cycle")}</th>
            <th scope="col" className={thClassName}>{t("page.miners.col.soldTotal")}</th>
            <th scope="col" className={thClassName}>{t("common.status")}</th>
            <th scope="col" className={thClassName}>{t("page.miners.col.added")}</th>
            <th scope="col" className={thClassName}>{t("common.actions")}</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {miners.map((miner) => {
            const isBusy = pendingId === miner.id;

            return (
              <tr key={miner.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="px-4 py-3 font-medium text-foreground">{miner.title}</td>
                <td className="px-4 py-3 text-muted">{minerTypeLabel(t, miner.type)}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">
                  {formatAmount(miner.pricenum)} {miner.pricecoin.toUpperCase()}
                </td>
                <td className="px-4 py-3 tabular-nums text-foreground">
                  {formatAmount(miner.dayoutnum)} {miner.outcoin.toUpperCase()}{t("page.miners.perDay")}
                </td>
                <td className="px-4 py-3 text-muted">{miner.cycle} {t("page.miners.days")}</td>
                <td className="px-4 py-3 tabular-nums text-foreground">
                  {formatAmount(miner.sellnum)} / {formatAmount(miner.allnum)}
                </td>
                <td className="px-4 py-3">
                  <span className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${statusClass(miner.status)}`}>
                    {minerStatusLabel(t, miner.status)}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">{formatTimestamp(miner.addtime)}</td>
                <td className="px-4 py-3">
                  <div className="flex flex-wrap gap-2">
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onEdit(miner)}
                      className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                    >
                      {t("common.edit")}
                    </button>
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onToggleStatus(miner)}
                      className="rounded border border-border px-2.5 py-1 text-xs font-medium text-muted transition hover:bg-surface-elevated disabled:opacity-40"
                    >
                      {miner.status === 1 ? t("action.suspend") : t("action.resume")}
                    </button>
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onDelete(miner)}
                      className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40"
                    >
                      {t("common.delete")}
                    </button>
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
