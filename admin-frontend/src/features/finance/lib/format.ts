export function formatTimestamp(value: string | number | null | undefined): string {
  if (value === null || value === undefined || value === "") return "—";

  if (typeof value === "number") {
    const ms = value > 1_000_000_000_000 ? value : value * 1000;
    return new Date(ms).toLocaleString("vi-VN");
  }

  const numeric = Number(value);
  if (!Number.isNaN(numeric) && /^\d+$/.test(String(value).trim())) {
    const ms = numeric > 1_000_000_000_000 ? numeric : numeric * 1000;
    return new Date(ms).toLocaleString("vi-VN");
  }

  const parsed = Date.parse(value);
  if (!Number.isNaN(parsed)) {
    return new Date(parsed).toLocaleString("vi-VN");
  }

  return String(value);
}

export function depositStatusClass(status: number): string {
  if (status === 1) return "bg-primary/15 text-primary";
  if (status === 2) return "bg-success/15 text-success";
  if (status === 3) return "bg-danger/15 text-danger";
  return "bg-surface-elevated text-muted";
}

export function withdrawalStatusClass(status: number): string {
  return depositStatusClass(status);
}
