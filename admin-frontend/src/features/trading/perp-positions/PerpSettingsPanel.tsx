"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useState } from "react";
import { fetchPerpSettings, updatePerpSettings } from "./api";
import { perpSettingsQueryKey } from "./query-keys";
import { useI18n } from "@/lib/i18n/useI18n";

export function PerpSettingsPanel() {
  const { t } = useI18n();
  const queryClient = useQueryClient();
  const { data, isLoading } = useQuery({
    queryKey: perpSettingsQueryKey,
    queryFn: fetchPerpSettings,
  });
  const [rate, setRate] = useState<string>("");

  const save = useMutation({
    mutationFn: (v: number) => updatePerpSettings(v),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: perpSettingsQueryKey });
    },
  });

  const current = data?.data?.perp_win_rate ?? 80;
  const displayRate = rate !== "" ? rate : String(current);

  return (
    <div className="flex flex-wrap items-end gap-3 rounded-xl border border-border bg-surface p-4">
      <div>
        <label htmlFor="perp-win-rate" className="block text-sm font-medium text-foreground">
          {t("page.perp.winRate")}
        </label>
        <p className="text-xs text-muted">{t("page.perp.winRateHint")}</p>
        <input
          id="perp-win-rate"
          type="number"
          min={0.01}
          max={1000}
          step={0.01}
          disabled={isLoading || save.isPending}
          value={displayRate}
          onChange={(e) => setRate(e.target.value)}
          className="mt-2 w-32 rounded-lg border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
        />
      </div>
      <button
        type="button"
        disabled={isLoading || save.isPending}
        onClick={() => save.mutate(Number(displayRate))}
        className="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-[var(--color-on-primary)] disabled:opacity-50"
      >
        {save.isPending ? t("common.updating") : t("common.save")}
      </button>
    </div>
  );
}
