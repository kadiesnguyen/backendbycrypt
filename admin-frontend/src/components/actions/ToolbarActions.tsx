"use client";

import type { ReactNode } from "react";

type ToolbarActionsProps = {
  children: ReactNode;
  className?: string;
};

export function ToolbarActions({ children, className = "" }: ToolbarActionsProps) {
  return (
    <div className={`flex flex-wrap items-center gap-2 ${className}`.trim()}>{children}</div>
  );
}
