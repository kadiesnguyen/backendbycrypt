"use client";
import { useEffect, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";
import { SystemParamsSkeleton } from "./SystemParamsSkeleton";
import { useSystemParams, useSystemParamsActions } from "./useSystemParams";

export function SystemParamsContainer() {
  const { t } = useI18n();
  const { data, isLoading, isError, error, refetch } = useSystemParams();
  const update = useSystemParamsActions();
  const [kefu, setKefu] = useState("");
  const [telegram, setTelegram] = useState("");
  const [footertext, setFootertext] = useState("");
  const [saveError, setSaveError] = useState<string | null>(null);
  useEffect(() => { if (data?.data) { setKefu(data.data.kefu ?? ""); setTelegram(data.data.telegram ?? ""); setFootertext(data.data.footertext ?? ""); } }, [data]);
  const handleSave = async (e: React.FormEvent) => { e.preventDefault(); setSaveError(null); try { await update.mutateAsync({ kefu, telegram, footertext }); } catch (err) { setSaveError(err instanceof Error ? err.message : t("common.saveFailed")); } };
  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.system.title" descriptionKey="page.system.description" />
      {isLoading ? <SystemParamsSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError ? (
        <form onSubmit={handleSave} className="max-w-2xl space-y-4 rounded-lg border border-border bg-surface p-6">
          {saveError ? <p className="text-sm text-danger">{saveError}</p> : null}
          <div><label className="block text-sm">CSKH (kefu)</label><input value={kefu} onChange={(e) => setKefu(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
          <div><label className="block text-sm">Telegram</label><input value={telegram} onChange={(e) => setTelegram(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
          <div><label className="block text-sm">Footer text</label><textarea rows={3} value={footertext} onChange={(e) => setFootertext(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
          <button type="submit" disabled={update.isPending} className="rounded bg-primary px-4 py-2 text-sm text-background">{t("common.save")}</button>
        </form>
      ) : null}
    </div>
  );
}
