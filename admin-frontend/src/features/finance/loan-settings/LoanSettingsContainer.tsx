"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import Link from "next/link";
import { useEffect, useState } from "react";
import { fetchLoanSettings, updateLoanSettings } from "../loans/api";

export function LoanSettingsContainer() {
  const { t } = useI18n();
  const queryClient = useQueryClient();
  const { data, isLoading, isError, error, refetch } = useQuery({
    queryKey: ["admin", "loan-settings"],
    queryFn: fetchLoanSettings,
  });

  const [enabled, setEnabled] = useState(true);
  const [minAmount, setMinAmount] = useState("1000");
  const [maxAmount, setMaxAmount] = useState("200000");
  const [durationDays, setDurationDays] = useState("7");
  const [dailyRate, setDailyRate] = useState("0.0004");
  const [lenderName, setLenderName] = useState("ICICI BANK");
  const [saveError, setSaveError] = useState<string | null>(null);
  const [saveSuccess, setSaveSuccess] = useState<string | null>(null);

  useEffect(() => {
    const settings = data?.data;
    if (!settings) return;
    setEnabled(settings.enabled);
    setMinAmount(settings.min_amount);
    setMaxAmount(settings.max_amount);
    setDurationDays(String(settings.duration_days));
    setDailyRate(settings.daily_interest_rate);
    setLenderName(settings.lender_name);
  }, [data]);

  const save = useMutation({
    mutationFn: updateLoanSettings,
    onSuccess: async () => {
      setSaveSuccess(t("common.saved"));
      setSaveError(null);
      await queryClient.invalidateQueries({ queryKey: ["admin", "loan-settings"] });
    },
    onError: (err) => {
      setSaveSuccess(null);
      setSaveError(err instanceof Error ? err.message : t("common.actionFailed"));
    },
  });

  const inputClass =
    "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <PageHeader
          titleKey="page.loanSettings.title"
          descriptionKey="page.loanSettings.description"
        />
        <Link
          href="/finance/loans"
          className="rounded border border-border px-3 py-2 text-sm font-medium text-foreground transition hover:bg-surface-elevated"
        >
          {t("page.loanSettings.backToList")}
        </Link>
      </div>

      {isLoading ? (
        <div className="h-48 animate-pulse rounded-lg border border-border bg-surface-elevated" />
      ) : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.loanSettings.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError ? (
        <form
          className="max-w-xl space-y-4 rounded-lg border border-border bg-surface p-4"
          onSubmit={(e) => {
            e.preventDefault();
            setSaveError(null);
            setSaveSuccess(null);
            save.mutate({
              enabled,
              min_amount: Number(minAmount),
              max_amount: Number(maxAmount),
              duration_days: Number(durationDays),
              daily_interest_rate: Number(dailyRate),
              lender_name: lenderName.trim(),
            });
          }}
        >
          {saveError ? (
            <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
              {saveError}
            </div>
          ) : null}
          {saveSuccess ? (
            <div role="status" className="rounded-lg border border-primary/40 bg-primary/10 px-4 py-3 text-sm text-primary">
              {saveSuccess}
            </div>
          ) : null}

          <label className="flex items-center gap-2 text-sm text-foreground">
            <input
              type="checkbox"
              checked={enabled}
              onChange={(e) => setEnabled(e.target.checked)}
            />
            {t("page.loanSettings.enabled")}
          </label>

          <div>
            <label className="block text-sm font-medium text-foreground" htmlFor="min_amount">
              {t("page.loanSettings.minAmount")}
            </label>
            <input id="min_amount" className={inputClass} value={minAmount} onChange={(e) => setMinAmount(e.target.value)} />
          </div>
          <div>
            <label className="block text-sm font-medium text-foreground" htmlFor="max_amount">
              {t("page.loanSettings.maxAmount")}
            </label>
            <input id="max_amount" className={inputClass} value={maxAmount} onChange={(e) => setMaxAmount(e.target.value)} />
          </div>
          <div>
            <label className="block text-sm font-medium text-foreground" htmlFor="duration_days">
              {t("page.loanSettings.durationDays")}
            </label>
            <input id="duration_days" className={inputClass} value={durationDays} onChange={(e) => setDurationDays(e.target.value)} />
          </div>
          <div>
            <label className="block text-sm font-medium text-foreground" htmlFor="daily_rate">
              {t("page.loanSettings.dailyRate")}
            </label>
            <input id="daily_rate" className={inputClass} value={dailyRate} onChange={(e) => setDailyRate(e.target.value)} />
            <p className="mt-1 text-xs text-muted">{t("page.loanSettings.dailyRateHint")}</p>
          </div>
          <div>
            <label className="block text-sm font-medium text-foreground" htmlFor="lender_name">
              {t("page.loanSettings.lenderName")}
            </label>
            <input id="lender_name" className={inputClass} value={lenderName} onChange={(e) => setLenderName(e.target.value)} />
          </div>

          <button
            type="submit"
            disabled={save.isPending}
            className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
          >
            {save.isPending ? t("common.saving") : t("common.save")}
          </button>
        </form>
      ) : null}
    </div>
  );
}
