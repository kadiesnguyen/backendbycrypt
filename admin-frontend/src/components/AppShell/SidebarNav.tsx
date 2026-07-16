"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useI18n } from "@/lib/i18n/useI18n";
import {
  DASHBOARD_PATH,
  MENU_BADGE_BY_PATH,
  resolveGroupLabelKey,
  resolveMenuLabelKey,
  resolveMenuPath,
  resolveMenuTree,
} from "@/lib/menu-routes";
import type { PendingCounts } from "@/lib/pending-counts/api";
import { usePendingCounts } from "@/lib/pending-counts/usePendingCounts";
import type { AdminMenuTree } from "@/features/auth/types";
import { SidebarGroupIcon } from "./SidebarGroupIcon";

type SidebarNavProps = {
  menuTree: AdminMenuTree;
  onNavigate?: () => void;
};

function formatBadgeCount(count: number): string {
  return count > 99 ? "99+" : String(count);
}

function SidebarLink({
  href,
  label,
  isActive,
  badgeCount,
  onNavigate,
}: {
  href: string;
  label: string;
  isActive: boolean;
  badgeCount?: number;
  onNavigate?: () => void;
}) {
  const hasBadge = badgeCount !== undefined && badgeCount > 0;

  return (
    <li>
      <Link
        href={href}
        aria-current={isActive ? "page" : undefined}
        onClick={onNavigate}
        className={`flex items-center justify-between gap-2 rounded px-3 py-2 text-sm font-medium transition ${
          isActive
            ? "bg-surface-elevated font-semibold text-primary"
            : hasBadge
              ? "text-danger hover:bg-surface-elevated hover:text-danger"
              : "text-foreground hover:bg-surface-elevated hover:text-primary"
        }`}
      >
        <span className="whitespace-nowrap" title={label}>
          {label}
        </span>
        {hasBadge ? (
          <span
            aria-label={`${badgeCount} pending`}
            className="inline-flex min-w-[1.25rem] shrink-0 items-center justify-center rounded-full bg-danger px-1.5 py-0.5 text-xs font-semibold text-white"
          >
            {formatBadgeCount(badgeCount)}
          </span>
        ) : null}
      </Link>
    </li>
  );
}

function badgeForPath(path: string, counts: PendingCounts | undefined): number | undefined {
  if (!counts) return undefined;
  const key = MENU_BADGE_BY_PATH[path];
  return key ? counts[key] : undefined;
}

export function SidebarBrand() {
  const { t } = useI18n();

  return (
    <div className="border-b border-border px-4 py-4">
      <p className="text-xs uppercase tracking-wide text-muted">{t("app.brand")}</p>
      <p className="text-lg font-semibold text-primary">{t("app.admin")}</p>
    </div>
  );
}

export function SidebarNav({ menuTree, onNavigate }: SidebarNavProps) {
  const pathname = usePathname();
  const { t } = useI18n();
  const { data: pendingData } = usePendingCounts();
  const pendingCounts = pendingData?.data;

  const groups = Object.entries(resolveMenuTree(menuTree).child);
  const isDashboardActive =
    pathname === DASHBOARD_PATH || pathname.startsWith(`${DASHBOARD_PATH}/`);

  return (
    <nav className="p-3">
      <ul className="space-y-1" role="list">
        <li>
          <SidebarLink
            href={DASHBOARD_PATH}
            label={t("nav.dashboard")}
            isActive={isDashboardActive}
            onNavigate={onNavigate}
          />
        </li>

        {groups.map(([groupName, items]) => (
          <li key={groupName}>
            <div className="flex items-center gap-2 px-3 pb-1 pt-3">
              <SidebarGroupIcon groupName={groupName} />
              <span className="whitespace-nowrap text-xs font-semibold uppercase tracking-wide text-muted">
                {t(resolveGroupLabelKey(groupName))}
              </span>
            </div>
            <ul className="ml-2 space-y-1 border-l border-border pl-2">
              {items.map((item) => {
                const href = resolveMenuPath(item.url);
                if (!href) {
                  return null;
                }
                const labelKey = resolveMenuLabelKey(item.url);
                const label = labelKey ? t(labelKey) : item.title;
                const isActive = pathname === href || pathname.startsWith(`${href}/`);
                const badgeCount = badgeForPath(href, pendingCounts);

                return (
                  <SidebarLink
                    key={String(item.id)}
                    href={href}
                    label={label}
                    isActive={isActive}
                    badgeCount={badgeCount}
                    onNavigate={onNavigate}
                  />
                );
              })}
            </ul>
          </li>
        ))}
      </ul>
      {groups.length === 0 ? (
        <p className="px-3 py-2 text-xs text-muted" role="status">
          {t("nav.noMenus")}
        </p>
      ) : null}
    </nav>
  );
}
