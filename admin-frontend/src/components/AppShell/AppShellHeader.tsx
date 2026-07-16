"use client";

import Link from "next/link";
import { useLogout } from "@/features/auth/useAuth";
import type { AdminUser } from "@/features/auth/types";
import { ThemeToggle } from "@/components/theme/ThemeToggle";
import { useI18n } from "@/lib/i18n/useI18n";
import type { Locale } from "@/lib/i18n/types";

type AppShellHeaderProps = {
  user: AdminUser;
  menuOpen?: boolean;
  onMenuToggle?: () => void;
};

function MenuIcon({ open }: { open: boolean }) {
  if (open) {
    return (
      <svg aria-hidden="true" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    );
  }

  return (
    <svg aria-hidden="true" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  );
}

export function AppShellHeader({ user, menuOpen = false, onMenuToggle }: AppShellHeaderProps) {
  const logout = useLogout();
  const { t, locale, setLocale } = useI18n();
  const displayName = user.name ?? user.username;

  return (
    <header className="border-b border-border bg-surface px-4 py-3 md:flex md:h-14 md:items-center md:justify-between md:px-6 md:py-0">
      <div className="flex min-w-0 items-center gap-3">
        {onMenuToggle ? (
          <button
            type="button"
            className="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded border border-border text-foreground transition hover:border-primary hover:text-primary md:hidden"
            onClick={onMenuToggle}
            aria-expanded={menuOpen}
            aria-controls="mobile-sidebar"
            aria-label={menuOpen ? t("nav.closeMenu") : t("nav.openMenu")}
          >
            <MenuIcon open={menuOpen} />
          </button>
        ) : null}
        <div className="min-w-0">
          <p className="truncate text-xs uppercase tracking-wide text-muted">{t("auth.signedInAs")}</p>
          <p className="truncate text-sm font-medium text-foreground">{displayName}</p>
        </div>
      </div>
      <div className="mt-3 flex flex-wrap items-center justify-end gap-2 border-t border-border pt-3 sm:gap-3 md:mt-0 md:border-t-0 md:pt-0">
        <ThemeToggle compact />
        <label className="flex items-center gap-2 text-sm text-muted">
          <span className="sr-only">{t("common.language")}</span>
          <select
            value={locale}
            onChange={(e) => setLocale(e.target.value as Locale)}
            className="max-w-[9rem] rounded border border-border bg-surface-elevated px-2 py-1 text-sm text-foreground"
          >
            <option value="vi">{t("common.locale.vi")}</option>
            <option value="en">{t("common.locale.en")}</option>
          </select>
        </label>
        <Link
          href="/account/password"
          className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:border-primary hover:text-primary"
        >
          {t("auth.changePassword")}
        </Link>
        <button
          type="button"
          onClick={logout}
          className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:border-primary hover:text-primary"
        >
          {t("auth.signOut")}
        </button>
      </div>
    </header>
  );
}
