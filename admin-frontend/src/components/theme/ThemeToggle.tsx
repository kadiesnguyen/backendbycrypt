"use client";

import { useTheme } from "@/lib/theme/ThemeProvider";
import type { Theme } from "@/lib/theme/types";
import { useI18n } from "@/lib/i18n/useI18n";

type ThemeToggleProps = {
  compact?: boolean;
};

export function ThemeToggle({ compact = false }: ThemeToggleProps) {
  const { theme, setTheme } = useTheme();
  const { t } = useI18n();

  return (
    <div
      className={compact ? "" : "inline-flex items-center gap-2"}
      role="group"
      aria-label={t("common.theme")}
    >
      {!compact ? <span className="text-sm text-muted">{t("common.theme")}</span> : null}
      <div className="inline-flex rounded-lg border border-border bg-surface-elevated p-0.5">
        {(["dark", "light"] as Theme[]).map((option) => {
          const active = theme === option;
          return (
            <button
              key={option}
              type="button"
              aria-pressed={active}
              onClick={() => setTheme(option)}
              className={`rounded-md px-2.5 py-1 text-xs font-medium transition ${
                active
                  ? "bg-primary text-[var(--color-on-primary)] shadow-sm"
                  : "text-muted hover:text-foreground"
              }`}
            >
              {option === "dark" ? t("common.theme.dark") : t("common.theme.light")}
            </button>
          );
        })}
      </div>
    </div>
  );
}
