export function formatNumber(value: number, decimals = 0): string {
  return new Intl.NumberFormat(undefined, {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  }).format(value);
}

export function formatCurrency(value: number): string {
  if (value >= 1_000_000) {
    return `${formatNumber(value / 1_000_000, 2)}M`;
  }
  if (value >= 10_000) {
    return `${formatNumber(value / 1_000, 1)}K`;
  }
  return formatNumber(value, value % 1 === 0 ? 0 : 2);
}

export function formatShortDate(dateStr: string): string {
  const date = new Date(`${dateStr}T00:00:00`);
  return new Intl.DateTimeFormat(undefined, { month: "short", day: "numeric" }).format(date);
}

export function formatTimestamp(ts: number | string): string {
  const date = typeof ts === "number" ? new Date(ts * 1000) : new Date(ts);
  if (Number.isNaN(date.getTime())) {
    return "—";
  }
  return new Intl.DateTimeFormat(undefined, {
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
}

/** Compact timestamp for dashboard tables (saves horizontal space). */
export function formatDashboardTimestamp(ts: number | string): string {
  const date = typeof ts === "number" ? new Date(ts * 1000) : new Date(ts);
  if (Number.isNaN(date.getTime())) {
    return "—";
  }
  return new Intl.DateTimeFormat(undefined, {
    day: "2-digit",
    month: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
}

export function formatRelativeTime(ts: number): string {
  const diff = Date.now() - ts * 1000;
  const minutes = Math.floor(diff / 60_000);
  if (minutes < 1) return "now";
  if (minutes < 60) return `${minutes}m`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours}h`;
  const days = Math.floor(hours / 24);
  return `${days}d`;
}
