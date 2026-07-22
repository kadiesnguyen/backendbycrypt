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
import { contractKongykLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatCompactTimestamp, formatTimestamp, kongykStatusClass } from "../lib/format";
import type { PerpPosition } from "./types";

type PerpPositionListProps = {
  positions: PerpPosition[];
  pendingActionId: number | null;
  onSetWinLoss: (position: PerpPosition, kongyk: 0 | 1 | 2) => void;
  onSettle: (position: PerpPosition) => void;
  embedded?: boolean;
  readonly?: boolean;
};

function sideLabel(side: string): string {
  return side === "long" ? "Long" : side === "short" ? "Short" : side;
}

function sideClass(side: string): string {
  return side === "long"
    ? "text-emerald-400 bg-emerald-400/10"
    : "text-rose-400 bg-rose-400/10";
}

function PositionActions({
  position,
  isBusy,
  onSetWinLoss,
  onSettle,
}: {
  position: PerpPosition;
  isBusy: boolean;
  onSetWinLoss: (position: PerpPosition, kongyk: 0 | 1 | 2) => void;
  onSettle: (position: PerpPosition) => void;
}) {
  const { t } = useI18n();

  return (
    <TableActions className="grid grid-cols-2 gap-1">
      <CompactActionButton
        variant="success"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(position, 1)}
      >
        {t("action.win")}
      </CompactActionButton>
      <CompactActionButton
        variant="danger"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(position, 2)}
      >
        {t("action.loss")}
      </CompactActionButton>
      <CompactActionButton
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSetWinLoss(position, 0)}
      >
        {t("action.normal")}
      </CompactActionButton>
      <CompactActionButton
        variant="primary"
        disabled={isBusy}
        className="justify-center"
        onClick={() => onSettle(position)}
      >
        {t("action.settleOrder")}
      </CompactActionButton>
    </TableActions>
  );
}

export function PerpPositionList({
  positions,
  pendingActionId,
  onSetWinLoss,
  onSettle,
  embedded = false,
  readonly = false,
}: PerpPositionListProps) {
  const { t } = useI18n();

  return (
    <TableShell className={embedded ? "rounded-none border-0" : undefined}>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              {t("common.username")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.coin")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.perp.qty")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.perp.leverage")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.direction")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.perp.margin")}
            </th>
            <th scope="col" className={thClassName}>
              {t("page.perp.unrealizedPnl")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.control")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.buyTime")}
            </th>
            {!readonly ? (
              <th scope="col" className={thClassName}>
                {t("common.actions")}
              </th>
            ) : null}
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {positions.map((position) => {
            const isBusy = pendingActionId === position.id;
            const controlLabel = contractKongykLabel(t, position.kongyk);
            const openFull = formatTimestamp(position.opened_at);
            const openCompact = formatCompactTimestamp(position.opened_at);

            return (
              <tr key={position.id} className="bg-surface transition hover:bg-surface-elevated">
                <AnnotatedCell label={t("common.username")} className="font-medium text-foreground">
                  <span className="break-all">{position.username}</span>
                </AnnotatedCell>
                <AnnotatedCell label={t("common.coin")} className="uppercase text-foreground">
                  {position.symbol}
                </AnnotatedCell>
                <AnnotatedCell label={t("page.perp.qty")} className="tabular-nums text-foreground" numeric>
                  {position.qty}
                </AnnotatedCell>
                <AnnotatedCell label={t("page.perp.leverage")} className="tabular-nums text-foreground" numeric>
                  {position.leverage}x
                </AnnotatedCell>
                <AnnotatedCell label={t("common.direction")}>
                  <span
                    className={`inline-flex whitespace-nowrap rounded px-2 py-0.5 text-xs font-semibold ${sideClass(position.side)}`}
                  >
                    {sideLabel(position.side)}
                  </span>
                </AnnotatedCell>
                <AnnotatedCell label={t("page.perp.margin")} className="tabular-nums text-foreground" numeric>
                  {position.margin}
                </AnnotatedCell>
                <AnnotatedCell label={t("page.perp.unrealizedPnl")} className="tabular-nums text-foreground" numeric>
                  {position.unrealized_pnl}
                </AnnotatedCell>
                <AnnotatedCell label={t("common.control")}>
                  <span
                    className={`inline-flex whitespace-nowrap rounded px-2 py-0.5 text-xs font-medium ${kongykStatusClass(position.kongyk)}`}
                  >
                    {controlLabel}
                  </span>
                </AnnotatedCell>
                <AnnotatedCell label={t("common.buyTime")} className="text-muted tabular-nums">
                  <span className="whitespace-nowrap" title={openFull}>
                    {openCompact}
                  </span>
                </AnnotatedCell>
                {!readonly ? (
                  <AnnotatedCell label={t("common.actions")} actions>
                    <PositionActions
                      position={position}
                      isBusy={isBusy}
                      onSetWinLoss={onSetWinLoss}
                      onSettle={onSettle}
                    />
                  </AnnotatedCell>
                ) : null}
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
