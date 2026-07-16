"use client";

import {
  AnnotatedCell,
  CompactActionButton,
  TableActions,
  TableShell,
  tableClassName,
  thClassName,
  theadClassName,
} from "@/components/list/TableShell";
import {
  contractHyzdLabel,
  contractKongykLabel,
  contractStatusLabel,
} from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import {
  formatCompactTimestamp,
  formatTimestamp,
  hyzdDirectionClass,
  kongykStatusClass,
} from "../lib/format";
import type { ContractOrder } from "./types";

type ContractOrderListProps = {
  orders: ContractOrder[];
  pendingActionId: number | null;
  onSetWinLoss: (order: ContractOrder, kongyk: 0 | 1 | 2) => void;
  onSettle: (order: ContractOrder) => void;
  embedded?: boolean;
};

function DirectionBadge({ hyzd }: { hyzd: number }) {
  const { t } = useI18n();
  const label = contractHyzdLabel(t, hyzd);

  return (
    <span
      className={`inline-flex whitespace-nowrap rounded px-2 py-0.5 text-xs font-semibold ${hyzdDirectionClass(hyzd)}`}
    >
      {label}
    </span>
  );
}

function OrderActions({
  order,
  isBusy,
  onSetWinLoss,
  onSettle,
}: {
  order: ContractOrder;
  isBusy: boolean;
  onSetWinLoss: (order: ContractOrder, kongyk: 0 | 1 | 2) => void;
  onSettle: (order: ContractOrder) => void;
}) {
  const { t } = useI18n();

  return (
    <TableActions className="grid grid-cols-2 gap-1">
      <CompactActionButton
        variant="success"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(order, 1)}
      >
        {t("action.win")}
      </CompactActionButton>
      <CompactActionButton
        variant="danger"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(order, 2)}
      >
        {t("action.loss")}
      </CompactActionButton>
      <CompactActionButton
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(order, 0)}
      >
        {t("action.normal")}
      </CompactActionButton>
      <CompactActionButton
        variant="primary"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSettle(order)}
      >
        {t("action.settleOrder")}
      </CompactActionButton>
    </TableActions>
  );
}

export function ContractOrderList({
  orders,
  pendingActionId,
  onSetWinLoss,
  onSettle,
  embedded = false,
}: ContractOrderListProps) {
  const { t } = useI18n();

  return (
    <TableShell className={embedded ? "rounded-none border-0" : undefined}>
      <table className={tableClassName}>
        <colgroup>
          <col className="w-[21%]" />
          <col className="w-[7%]" />
          <col className="w-[6%]" />
          <col className="w-[9%]" />
          <col className="w-[9%]" />
          <col className="w-[14%]" />
          <col className="w-[14%]" />
          <col className="w-[20%]" />
        </colgroup>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              {t("common.username")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.coin")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.amount")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.direction")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.control")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.status")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.buyTime")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.actions")}
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {orders.map((order) => {
            const isBusy = pendingActionId === order.id;
            const statusLabel = contractStatusLabel(t, order.status);
            const controlLabel = contractKongykLabel(t, order.kongyk);
            const buyTimeFull = formatTimestamp(order.buytime);
            const buyTimeCompact = formatCompactTimestamp(order.buytime);

            return (
              <tr key={order.id} className="bg-surface transition hover:bg-surface-elevated">
                <AnnotatedCell
                  label={t("common.username")}
                  className="font-medium text-foreground"
                >
                  <span className="break-all">{order.username}</span>
                </AnnotatedCell>
                <AnnotatedCell label={t("common.coin")} className="uppercase text-foreground">
                  {order.coinname}
                </AnnotatedCell>
                <AnnotatedCell
                  label={t("common.amount")}
                  className="tabular-nums text-foreground"
                  numeric
                >
                  {order.num}
                </AnnotatedCell>
                <AnnotatedCell label={t("common.direction")}>
                  <DirectionBadge hyzd={order.hyzd} />
                </AnnotatedCell>
                <AnnotatedCell label={t("common.control")}>
                  <span
                    className={`inline-flex whitespace-nowrap rounded px-2 py-0.5 text-xs font-medium ${kongykStatusClass(order.kongyk)}`}
                  >
                    {controlLabel}
                  </span>
                </AnnotatedCell>
                <AnnotatedCell label={t("common.status")} className="text-muted">
                  <span className="whitespace-nowrap">{statusLabel}</span>
                </AnnotatedCell>
                <AnnotatedCell
                  label={t("common.buyTime")}
                  className="text-muted tabular-nums"
                >
                  <span className="whitespace-nowrap" title={buyTimeFull}>
                    {buyTimeCompact}
                  </span>
                </AnnotatedCell>
                <AnnotatedCell label={t("common.actions")} actions>
                  <OrderActions
                    order={order}
                    isBusy={isBusy}
                    onSetWinLoss={onSetWinLoss}
                    onSettle={onSettle}
                  />
                </AnnotatedCell>
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
