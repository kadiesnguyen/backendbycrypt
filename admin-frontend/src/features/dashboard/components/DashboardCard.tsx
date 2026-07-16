import type { ReactNode } from "react";
import Link from "next/link";

type DashboardCardProps = {
  title: string;
  action?: { label: string; href: string };
  children: ReactNode;
  className?: string;
  bodyClassName?: string;
};

export function DashboardCard({
  title,
  action,
  children,
  className = "",
  bodyClassName = "",
}: DashboardCardProps) {
  return (
    <section
      className={`dashboard-card flex min-h-0 flex-col overflow-hidden ${className}`.trim()}
    >
      <header className="flex shrink-0 items-center justify-between gap-3 border-b border-border px-4 py-3">
        <h3 className="min-w-0 truncate text-base font-semibold text-foreground">{title}</h3>
        {action ? (
          <Link
            href={action.href}
            className="shrink-0 whitespace-nowrap text-sm font-medium text-primary transition hover:opacity-80"
          >
            {action.label}
          </Link>
        ) : null}
      </header>
      <div className={`min-h-0 min-w-0 flex-1 ${bodyClassName}`.trim()}>{children}</div>
    </section>
  );
}
