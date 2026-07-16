"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";

import { useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { ErrorState } from "@/components/ui/ErrorState";
import { ContractMarketsList } from "./ContractMarketsList";
import { ContractMarketForm, ContractSettingsForm } from "./ContractSettingsForms";
import { ContractSettingsSkeleton } from "./ContractSettingsSkeleton";
import {
  useContractMarketActions,
  useMarketFormMeta,
} from "./useContractMarketActions";
import { useContractMarkets } from "./useContractMarkets";
import { useContractSettings } from "./useContractSettings";
import { useContractSettingsActions } from "./useContractSettingsActions";
import type { TradingMarket, UpdateContractSettingPayload, UpsertMarketPayload } from "./types";

export function ContractSettingsContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");

  const [settingsError, setSettingsError] = useState<string | null>(null);
  const [settingsSuccess, setSettingsSuccess] = useState<string | null>(null);
  const [marketError, setMarketError] = useState<string | null>(null);
  const [editingMarket, setEditingMarket] = useState<TradingMarket | null>(null);
  const [isCreatingMarket, setIsCreatingMarket] = useState(false);

  const marketsParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 10,
    }),
    [page],
  );

  const {
    data: settingsData,
    isLoading: settingsLoading,
    isError: settingsIsError,
    error: settingsQueryError,
    refetch: refetchSettings,
  } = useContractSettings();

  const {
    data: marketsData,
    isLoading: marketsLoading,
    isError: marketsIsError,
    error: marketsQueryError,
    refetch: refetchMarkets,
    isFetching: marketsFetching,
  } = useContractMarkets(marketsParams);

  const { data: formMetaData } = useMarketFormMeta();
  const { save: saveSettings } = useContractSettingsActions();
  const { create: createMarket, update: updateMarket } = useContractMarketActions();

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") {
          next.delete(key);
        } else {
          next.set(key, value);
        }
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  const handleSaveSettings = async (payload: Record<string, string>) => {
    setSettingsError(null);
    setSettingsSuccess(null);

    try {
      const result = await saveSettings.mutateAsync(payload as unknown as UpdateContractSettingPayload);
      setSettingsSuccess(result.message || t("page.contractSettings.saved"));
    } catch (err) {
      setSettingsError(err instanceof Error ? err.message : t("page.contractSettings.saveFailed"));
    }
  };

  const handleSaveMarket = async (payload: UpsertMarketPayload) => {
    setMarketError(null);

    try {
      if (isCreatingMarket) {
        await createMarket.mutateAsync(payload);
        setIsCreatingMarket(false);
      } else if (editingMarket) {
        await updateMarket.mutateAsync({ id: editingMarket.id, payload });
        setEditingMarket(null);
      }
    } catch (err) {
      setMarketError(err instanceof Error ? err.message : t("page.contractSettings.marketSaveFailed"));
    }
  };

  const markets = marketsData?.data ?? [];
  const marketsMeta = marketsData?.meta;
  const isLoading = settingsLoading || marketsLoading;
  const isMarketSaving = createMarket.isPending || updateMarket.isPending;
  const showMarketForm = isCreatingMarket || editingMarket !== null;

  return (
    <div className="space-y-8">
      <PageHeader titleKey="page.contractSettings.title" descriptionKey="page.contractSettings.description" />

      {isLoading ? <ContractSettingsSkeleton /> : null}

      {!isLoading && settingsIsError ? (
        <ErrorState
          message={
            settingsQueryError instanceof Error
              ? settingsQueryError.message
              : t("page.contractSettings.loadFailed")
          }
          retry={() => refetchSettings()}
        />
      ) : null}

      {!isLoading && !settingsIsError ? (
        <ContractSettingsForm
          setting={settingsData?.data ?? null}
          isSaving={saveSettings.isPending}
          saveError={settingsError}
          saveSuccess={settingsSuccess}
          onSave={handleSaveSettings}
        />
      ) : null}

      <section aria-labelledby="markets-heading" className="space-y-4">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h2 id="markets-heading" className="text-lg font-semibold text-foreground">
              {t("page.contractSettings.marketsTitle")}
            </h2>
            <p className="mt-1 text-sm text-muted">{t("page.contractSettings.marketsHint")}</p>
          </div>
          {!showMarketForm ? (
            <button
              type="button"
              onClick={() => {
                setMarketError(null);
                setEditingMarket(null);
                setIsCreatingMarket(true);
              }}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
            >
              {t("page.contractSettings.addMarket")}
            </button>
          ) : null}
        </div>

        {showMarketForm ? (
          <ContractMarketForm
            market={editingMarket}
            formMeta={formMetaData?.data}
            isCreating={isCreatingMarket}
            isSaving={isMarketSaving}
            saveError={marketError}
            onSave={handleSaveMarket}
            onCancel={() => {
              if (!isMarketSaving) {
                setEditingMarket(null);
                setIsCreatingMarket(false);
                setMarketError(null);
              }
            }}
          />
        ) : null}

        {marketsIsError ? (
          <ErrorState
            message={
              marketsQueryError instanceof Error
                ? marketsQueryError.message
                : t("page.contractSettings.marketsLoadFailed")
            }
            retry={() => refetchMarkets()}
          />
        ) : null}

        {!marketsIsError && !marketsLoading && markets.length === 0 ? (
          <div role="status" className="rounded-lg border border-border bg-surface px-6 py-12 text-center">
            <h3 className="text-sm font-medium text-foreground">{t("page.contractSettings.noMarkets")}</h3>
            <p className="mt-1 text-sm text-muted">{t("page.contractSettings.noMarketsHint")}</p>
          </div>
        ) : null}

        {!marketsIsError && markets.length > 0 ? (
          <>
            <div className="flex items-center justify-between text-sm text-muted">
              <p>
                {marketsMeta ? (
                  <>
                    {t("common.pageInfo", {
                      current: String(marketsMeta.current_page),
                      last: String(marketsMeta.last_page),
                      total: String(marketsMeta.total),
                    })}
                  </>
                ) : null}
                {marketsFetching ? <span className="ml-2 text-primary">{t("common.updating")}</span> : null}
              </p>
            </div>
            <ContractMarketsList
              markets={markets}
              editingId={editingMarket?.id ?? null}
              onEdit={(market) => {
                setMarketError(null);
                setIsCreatingMarket(false);
                setEditingMarket(market);
              }}
            />
            {marketsMeta && marketsMeta.last_page > 1 ? (
              <nav aria-label={t("common.pagination")} className="flex items-center justify-center gap-2">
                <button
                  type="button"
                  disabled={marketsMeta.current_page <= 1}
                  onClick={() => updateParams({ page: String(marketsMeta.current_page - 1) })}
                  className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                >
                  {t("common.previous")}
                </button>
                <span className="px-2 text-sm text-muted">
                  {marketsMeta.current_page} / {marketsMeta.last_page}
                </span>
                <button
                  type="button"
                  disabled={marketsMeta.current_page >= marketsMeta.last_page}
                  onClick={() => updateParams({ page: String(marketsMeta.current_page + 1) })}
                  className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                >
                  {t("common.next")}
                </button>
              </nav>
            ) : null}
          </>
        ) : null}
      </section>
    </div>
  );
}
