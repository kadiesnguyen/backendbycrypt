"use client";
import { useEffect, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";
import { SpotSettingsSkeleton } from "./SpotSettingsSkeleton";
import { useSpotSettings, useSpotSettingsActions } from "./useSpotSettings";

export function SpotSettingsContainer() {
  const { t } = useI18n();
  const { data, isLoading, isError, error, refetch } = useSpotSettings();
  const update = useSpotSettingsActions();
  const [bbKstime, setBbKstime] = useState("");
  const [saveError, setSaveError] = useState<string | null>(null);
  useEffect(() => { if (data?.data) setBbKstime(data.data.bb_kstime ?? ""); }, [data]);
  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault(); setSaveError(null);
    try { await update.mutateAsync({ bb_kstime: bbKstime }); }
    catch (err) { setSaveError(err instanceof Error ? err.message : t("common.saveFailed")); }
  };
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.spotSettings.title" descriptionKey="page.spotSettings.description" />
      {isLoading ? <SpotSettingsSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError ? (
        <form onSubmit={handleSave} className="max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6">
          {saveError ? <p className="text-sm text-danger">{saveError}</p> : null}
          <div><label className="block text-sm text-foreground">bb_kstime</label><input value={bbKstime} onChange={(e) => setBbKstime(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
          <button type="submit" disabled={update.isPending} className="rounded bg-primary px-4 py-2 text-sm text-background">{t("common.save")}</button>
        </form>
      ) : null}
    </div>
  );
}
