"use client";

import type { DepositWithdrawalPoint } from "../types";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatCurrency, formatShortDate } from "../format";

type DepositWithdrawalChartProps = {
  data: DepositWithdrawalPoint[];
};

const CHART_HEIGHT = 160;
const PADDING = { top: 12, right: 8, bottom: 28, left: 8 };

export function DepositWithdrawalChart({ data }: DepositWithdrawalChartProps) {
  const { t } = useI18n();
  const width = 280;
  const innerW = width - PADDING.left - PADDING.right;
  const innerH = CHART_HEIGHT - PADDING.top - PADDING.bottom;

  const maxValue = Math.max(
    ...data.flatMap((d) => [d.deposit, d.withdrawal]),
    1,
  );
  const groupWidth = innerW / data.length;
  const barWidth = Math.min(14, groupWidth * 0.32);

  return (
    <div className="flex h-full flex-col">
      <div className="mb-2 flex items-center gap-4 px-1">
        <span className="flex items-center gap-1.5 text-xs text-muted">
          <span className="inline-block h-2 w-2 rounded-sm bg-success" />
          {t("page.dashboard.legend.deposit")}
        </span>
        <span className="flex items-center gap-1.5 text-xs text-muted">
          <span className="inline-block h-2 w-2 rounded-sm bg-danger" />
          {t("page.dashboard.legend.withdrawal")}
        </span>
      </div>
      <svg
        viewBox={`0 0 ${width} ${CHART_HEIGHT}`}
        className="h-full w-full flex-1"
        role="img"
        aria-label="Deposit vs withdrawal chart"
        preserveAspectRatio="none"
      >
        {data.map((d, i) => {
          const centerX = PADDING.left + i * groupWidth + groupWidth / 2;
          const depositH = (d.deposit / maxValue) * innerH;
          const withdrawalH = (d.withdrawal / maxValue) * innerH;
          const baseY = PADDING.top + innerH;

          return (
            <g key={d.date}>
              <rect
                x={centerX - barWidth - 2}
                y={baseY - depositH}
                width={barWidth}
                height={depositH}
                rx="2"
                fill="var(--color-success)"
                opacity="0.85"
              />
              <rect
                x={centerX + 2}
                y={baseY - withdrawalH}
                width={barWidth}
                height={withdrawalH}
                rx="2"
                fill="var(--color-error)"
                opacity="0.85"
              />
              <title>
                {formatShortDate(d.date)}: D {formatCurrency(d.deposit)} / W{" "}
                {formatCurrency(d.withdrawal)}
              </title>
              <text
                x={centerX}
                y={CHART_HEIGHT - 6}
                textAnchor="middle"
                fill="var(--color-text-muted)"
                fontSize="12"
              >
                {formatShortDate(d.date).replace(" ", "")}
              </text>
            </g>
          );
        })}
      </svg>
    </div>
  );
}
