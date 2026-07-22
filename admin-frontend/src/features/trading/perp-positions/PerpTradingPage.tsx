"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { PerpFillsContainer } from "@/features/trading/perp-positions/PerpFillsContainer";
import {
  PerpPositionHistoryContainer,
  PerpPositionListContainer,
} from "@/features/trading/perp-positions/PerpPositionListContainer";
import { useI18n } from "@/lib/i18n/useI18n";

const tabs = [
  { href: "/trading/perp", key: "open" as const },
  { href: "/trading/perp/history", key: "history" as const },
  { href: "/trading/perp/fills", key: "fills" as const },
];

export function PerpTradingPage({ tab }: { tab: "open" | "history" | "fills" }) {
  const { t } = useI18n();
  const pathname = usePathname();

  return (
    <div className="space-y-6">
      <nav className="flex flex-wrap gap-2 border-b border-border pb-3">
        {tabs.map((item) => {
          const active = pathname === item.href;
          const label =
            item.key === "open"
              ? t("page.perp.tabOpen")
              : item.key === "history"
                ? t("page.perp.tabHistory")
                : t("page.perp.tabFills");
          return (
            <Link
              key={item.href}
              href={item.href}
              className={`rounded-lg px-3 py-1.5 text-sm font-medium transition ${
                active ? "bg-primary/15 text-primary" : "text-muted hover:text-foreground"
              }`}
            >
              {label}
            </Link>
          );
        })}
      </nav>
      {tab === "open" ? <PerpPositionListContainer /> : null}
      {tab === "history" ? <PerpPositionHistoryContainer /> : null}
      {tab === "fills" ? <PerpFillsContainer /> : null}
    </div>
  );
}
