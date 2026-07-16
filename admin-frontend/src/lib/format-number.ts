import type { ReactNode } from "react";

const DEFAULT_LOCALE = "en-US";

type FormatAmountOptions = {
  minimumFractionDigits?: number;
  maximumFractionDigits?: number;
  locale?: string;
};

/** Column keys that should display as formatted amounts in data tables. */
export const AMOUNT_COLUMN_KEYS = new Set([
  "num",
  "amount",
  "coinnum",
  "usdtnum",
  "mum",
  "fee",
  "price",
  "new_price",
  "volume",
  "change",
  "usdt",
  "btc",
  "eth",
  "ltc",
  "sol",
  "xrp",
  "from_amount",
  "to_amount",
  "convert_rate",
  "pricenum",
  "dayoutnum",
  "sellnum",
  "allnum",
  "min",
  "max",
  "open",
  "one",
  "two",
  "all",
  "pending",
  "assets",
]);

/** Display numeric values with international grouping (e.g. 1,234,567.89). */
export function formatAmount(
  value: number | string | null | undefined,
  options?: FormatAmountOptions,
): string {
  if (value === null || value === undefined || value === "") {
    return "—";
  }

  const raw = typeof value === "string" ? value.trim().replace(/,/g, "") : value;
  const num = typeof raw === "number" ? raw : Number(raw);

  if (!Number.isFinite(num)) {
    return String(value);
  }

  const minimumFractionDigits = options?.minimumFractionDigits ?? 2;
  const maximumFractionDigits = options?.maximumFractionDigits ?? 8;

  return new Intl.NumberFormat(options?.locale ?? DEFAULT_LOCALE, {
    minimumFractionDigits,
    maximumFractionDigits,
  }).format(num);
}

/** Format table cell values when the column is numeric. */
export function formatTableAmountValue(
  columnKey: string,
  value: unknown,
  options?: FormatAmountOptions,
): ReactNode {
  if (!AMOUNT_COLUMN_KEYS.has(columnKey)) {
    return value as ReactNode;
  }

  if (value === null || value === undefined || value === "") {
    return "—";
  }

  if (typeof value === "number" || typeof value === "string") {
    return formatAmount(value, options);
  }

  return value as ReactNode;
}
