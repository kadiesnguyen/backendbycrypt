"use client";

import { formatTableAmountValue } from "@/lib/format-number";
import { createContext, useContext } from "react";
import type { DataTableColumn } from "./ListPageParts";

const TableColumnsContext = createContext<DataTableColumn[]>([]);

export function useTableColumns() {
  return useContext(TableColumnsContext);
}

type TableShellProps = {
  children: React.ReactNode;
  className?: string;
};

export function TableShell({ children, className = "" }: TableShellProps) {
  return (
    <div
      className={`w-full max-w-full overflow-hidden rounded-lg border border-border ${className}`.trim()}
    >
      {children}
    </div>
  );
}

export const tableClassName =
  "admin-responsive-table w-full table-auto divide-y divide-border text-left text-sm";

export const thClassName =
  "px-2 py-2 font-medium text-muted first:pl-3 last:pr-3 md:px-3 md:py-2.5 md:first:pl-4 md:last:pr-4";

export const theadClassName =
  "border-b border-border bg-surface-elevated text-[11px] uppercase tracking-wide text-muted";

type DataTableProviderProps = {
  columns: DataTableColumn[];
  children: React.ReactNode;
};

export function DataTableProvider({ columns, children }: DataTableProviderProps) {
  return <TableColumnsContext.Provider value={columns}>{children}</TableColumnsContext.Provider>;
}

type DataTableCellProps = {
  columnKey: string;
  children: React.ReactNode;
  className?: string;
  actions?: boolean;
};

export function DataTableCell({
  columnKey,
  children,
  className = "",
  actions = false,
}: DataTableCellProps) {
  const columns = useTableColumns();
  const column = columns.find((col) => col.key === columnKey);
  const label = column?.label ?? "";
  const display =
    typeof children === "number" || typeof children === "string"
      ? formatTableAmountValue(columnKey, children)
      : children;

  return (
    <td
      data-label={label}
      className={`min-w-0 px-2 py-2 align-middle first:pl-3 last:pr-3 md:px-3 md:py-2.5 md:first:pl-4 md:last:pr-4 ${
        actions ? "admin-table-actions" : ""
      } ${className}`.trim()}
    >
      {display}
    </td>
  );
}

type AnnotatedCellProps = {
  label: string;
  children: React.ReactNode;
  className?: string;
  actions?: boolean;
  /** When true, numeric children are formatted with thousands separators and decimals. */
  numeric?: boolean;
};

/** For standalone tables outside DataTable (e.g. ContractOrderList). */
export function AnnotatedCell({
  label,
  children,
  className = "",
  actions = false,
  numeric = false,
}: AnnotatedCellProps) {
  const display =
    numeric && (typeof children === "number" || typeof children === "string")
      ? formatTableAmountValue("num", children)
      : children;

  return (
    <td
      data-label={label}
      className={`min-w-0 px-2 py-2 align-middle first:pl-3 last:pr-3 md:px-3 md:py-2.5 md:first:pl-4 md:last:pr-4 ${
        actions ? "admin-table-actions" : ""
      } ${className}`.trim()}
    >
      {display}
    </td>
  );
}

type CompactActionButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: "success" | "danger" | "neutral" | "primary";
};

export function CompactActionButton({
  variant = "neutral",
  className = "",
  type = "button",
  ...props
}: CompactActionButtonProps) {
  const variantClass =
    variant === "success"
      ? "bg-success text-[var(--color-on-primary)] hover:opacity-90"
      : variant === "danger"
        ? "border border-danger text-danger hover:bg-danger/10"
        : variant === "primary"
          ? "bg-primary text-[var(--color-on-primary)] hover:opacity-90"
          : "border border-border text-muted hover:bg-surface-elevated";

  return (
    <button
      type={type}
      className={`inline-flex shrink-0 items-center justify-center whitespace-nowrap rounded px-2 py-1 text-xs font-medium transition disabled:opacity-40 ${variantClass} ${className}`.trim()}
      {...props}
    />
  );
}

export function TableActions({
  children,
  className = "",
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return <div className={`flex max-w-full flex-wrap gap-1.5 ${className}`.trim()}>{children}</div>;
}
