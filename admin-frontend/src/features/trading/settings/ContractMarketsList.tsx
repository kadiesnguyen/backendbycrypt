"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { activeInactiveLabel, onOffLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatAmount } from "@/lib/format-number";
import { marketStatusClass } from "../lib/format";
import type { TradingMarket } from "./types";

type ContractMarketsListProps = {
  markets: TradingMarket[];
  editingId: number | null;
  onEdit: (market: TradingMarket) => void;
};

export function ContractMarketsList({ markets, editingId, onEdit }: ContractMarketsListProps) {
  const { t } = useI18n();

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              {t("page.contractSettings.marketName")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.contractSettings.round")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.contractSettings.marketPrice")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.contractSettings.marketTrade")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.status")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.actions")}
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {markets.map((market) => (
            <tr
              key={market.id}
              className={`bg-surface transition hover:bg-surface-elevated ${editingId === market.id ? "ring-1 ring-primary/40" : ""}`}
            >
              <td className="px-4 py-3 font-medium uppercase text-foreground">{market.name}</td>
              <td className="px-4 py-3 text-muted">{market.round}</td>
              <td className="px-4 py-3 tabular-nums text-foreground">
                {market.new_price != null ? formatAmount(market.new_price) : "—"}
              </td>
              <td className="px-4 py-3 text-muted">{onOffLabel(t, Number(market.trade) === 1)}</td>
              <td className="px-4 py-3">
                <span
                  className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${marketStatusClass(market.status)}`}
                >
                  {activeInactiveLabel(t, Number(market.status) === 1)}
                </span>
              </td>
              <td className="px-4 py-3">
                <button
                  type="button"
                  onClick={() => onEdit(market)}
                  className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated"
                >
                  {t("common.edit")}
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </TableShell>
  );
}
