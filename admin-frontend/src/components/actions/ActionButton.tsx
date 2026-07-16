"use client";

import type { ButtonHTMLAttributes } from "react";

export type ActionButtonVariant = "primary" | "success" | "warning" | "danger" | "ghost";

type ActionButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: ActionButtonVariant;
};

const variantClasses: Record<ActionButtonVariant, string> = {
  primary:
    "rounded bg-primary px-2.5 py-1 text-xs font-medium text-background transition hover:opacity-90 disabled:opacity-40",
  success:
    "rounded border border-success px-2.5 py-1 text-xs font-medium text-success transition hover:bg-success/10 disabled:opacity-40",
  warning:
    "rounded border border-warning px-2.5 py-1 text-xs font-medium text-warning transition hover:bg-warning/10 disabled:opacity-40",
  danger:
    "rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40",
  ghost:
    "rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40",
};

export function ActionButton({
  variant = "ghost",
  className = "",
  type = "button",
  ...props
}: ActionButtonProps) {
  return (
    <button
      type={type}
      className={`${variantClasses[variant]} ${className}`.trim()}
      {...props}
    />
  );
}
