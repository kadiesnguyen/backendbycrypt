export function formatTimestamp(value: string | number | null | undefined): string {
  const date = parseTimestamp(value);
  if (!date) {
    if (value === null || value === undefined || value === "") return "—";
    return String(value);
  }
  return date.toLocaleString("vi-VN");
}

/** Compact table display: dd/MM/yyyy HH:mm */
export function formatCompactTimestamp(value: string | number | null | undefined): string {
  const date = parseTimestamp(value);
  if (!date) return "—";

  const pad = (part: number) => String(part).padStart(2, "0");
  return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function parseTimestamp(value: string | number | null | undefined): Date | null {
  if (value === null || value === undefined || value === "") return null;

  if (typeof value === "number") {
    const ms = value > 1_000_000_000_000 ? value : value * 1000;
    return new Date(ms);
  }

  const numeric = Number(value);
  if (!Number.isNaN(numeric) && /^\d+$/.test(String(value).trim())) {
    const ms = numeric > 1_000_000_000_000 ? numeric : numeric * 1000;
    return new Date(ms);
  }

  const parsed = Date.parse(value);
  if (!Number.isNaN(parsed)) {
    return new Date(parsed);
  }

  return null;
}

export function hyzdDirectionClass(hyzd: number): string {
  if (hyzd === 1) return "bg-success/15 text-success";
  if (hyzd === 2) return "bg-danger/15 text-danger";
  return "bg-surface-elevated text-muted";
}

export function kongykStatusClass(kongyk: number): string {
  if (kongyk === 1) return "bg-success/15 text-success";
  if (kongyk === 2) return "bg-danger/15 text-danger";
  return "bg-surface-elevated text-muted";
}

export function queueResultClass(result: string): string {
  if (result === "WIN") return "bg-success/15 text-success";
  if (result === "LOSS") return "bg-danger/15 text-danger";
  return "bg-surface-elevated text-muted";
}

export function marketStatusClass(status: number | string | null | undefined): string {
  const value = Number(status);
  if (value === 1) return "bg-success/15 text-success";
  if (value === 0) return "bg-danger/15 text-danger";
  return "bg-surface-elevated text-muted";
}
