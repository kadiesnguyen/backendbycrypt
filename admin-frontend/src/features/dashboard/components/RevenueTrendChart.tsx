import type { RevenueTrendPoint } from "../types";
import { formatCurrency, formatShortDate } from "../format";

type RevenueTrendChartProps = {
  data: RevenueTrendPoint[];
};

const CHART_HEIGHT = 160;
const PADDING = { top: 12, right: 8, bottom: 28, left: 44 };

export function RevenueTrendChart({ data }: RevenueTrendChartProps) {
  const width = 640;
  const innerW = width - PADDING.left - PADDING.right;
  const innerH = CHART_HEIGHT - PADDING.top - PADDING.bottom;

  const maxAmount = Math.max(...data.map((d) => d.amount), 1);
  const points = data.map((d, i) => {
    const x = PADDING.left + (i / Math.max(data.length - 1, 1)) * innerW;
    const y = PADDING.top + innerH - (d.amount / maxAmount) * innerH;
    return { x, y, ...d };
  });

  const linePath = points.map((p, i) => `${i === 0 ? "M" : "L"} ${p.x} ${p.y}`).join(" ");
  const areaPath = `${linePath} L ${points[points.length - 1]?.x ?? PADDING.left} ${PADDING.top + innerH} L ${points[0]?.x ?? PADDING.left} ${PADDING.top + innerH} Z`;

  const yTicks = [0, 0.25, 0.5, 0.75, 1].map((ratio) => ({
    y: PADDING.top + innerH - ratio * innerH,
    value: maxAmount * ratio,
  }));

  return (
    <div className="h-full w-full">
      <svg
        viewBox={`0 0 ${width} ${CHART_HEIGHT}`}
        className="h-full w-full"
        role="img"
        aria-label="Revenue trend chart"
        preserveAspectRatio="none"
      >
        <defs>
          <linearGradient id="revenue-area" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="var(--color-primary)" stopOpacity="0.35" />
            <stop offset="100%" stopColor="var(--color-primary)" stopOpacity="0" />
          </linearGradient>
        </defs>

        {yTicks.map((tick) => (
          <g key={tick.y}>
            <line
              x1={PADDING.left}
              y1={tick.y}
              x2={width - PADDING.right}
              y2={tick.y}
              stroke="var(--color-border)"
              strokeOpacity="0.5"
              strokeDasharray="3 4"
            />
            <text
              x={PADDING.left - 6}
              y={tick.y + 4}
              textAnchor="end"
              fill="var(--color-text-muted)"
              fontSize="10"
            >
              {formatCurrency(tick.value)}
            </text>
          </g>
        ))}

        <path d={areaPath} fill="url(#revenue-area)" />
        <path
          d={linePath}
          fill="none"
          stroke="var(--color-primary)"
          strokeWidth="2"
          strokeLinejoin="round"
          strokeLinecap="round"
        />

        {points.map((p) => (
          <circle key={p.date} cx={p.x} cy={p.y} r="3" fill="var(--color-primary)" />
        ))}

        {points
          .filter((_, i) => i % 2 === 0 || i === points.length - 1)
          .map((p) => (
            <text
              key={`label-${p.date}`}
              x={p.x}
              y={CHART_HEIGHT - 6}
              textAnchor="middle"
              fill="var(--color-text-muted)"
              fontSize="10"
            >
              {formatShortDate(p.date)}
            </text>
          ))}
      </svg>
    </div>
  );
}
