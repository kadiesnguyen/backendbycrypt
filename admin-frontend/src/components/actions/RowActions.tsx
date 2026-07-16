"use client";

import type { ReactNode } from "react";

type RowActionsProps = {
  children: ReactNode;
  className?: string;
};

export function RowActions({ children, className = "" }: RowActionsProps) {
  return <div className={`flex flex-wrap gap-2 ${className}`.trim()}>{children}</div>;
}
