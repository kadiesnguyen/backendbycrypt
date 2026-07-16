"use client";

import { PageHeader } from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";

import { useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import { SiteConfigForm } from "./SiteConfigForm";
import { SiteConfigSkeleton } from "./SiteConfigSkeleton";
import { useSiteConfig } from "./useSiteConfig";
import { useSiteConfigActions } from "./useSiteConfigActions";
import type { SiteConfigUpdatePayload } from "./types";

export function SiteConfigContainer() {
  const { t } = useI18n();
  const [saveError, setSaveError] = useState<string | null>(null);
  const [saveSuccess, setSaveSuccess] = useState<string | null>(null);

  const { data, isLoading, isError, error, refetch } = useSiteConfig();
  const { save } = useSiteConfigActions();

  const handleSave = async (payload: SiteConfigUpdatePayload) => {
    setSaveError(null);
    setSaveSuccess(null);

    try {
      const result = await save.mutateAsync(payload);
      setSaveSuccess(result.message || "Configuration saved.");
    } catch (err) {
      setSaveError(err instanceof Error ? err.message : "Failed to save configuration.");
    }
  };

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.site.title" descriptionKey="page.site.description" />

      {isLoading ? <SiteConfigSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : "Failed to load site configuration."}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError ? (
        <SiteConfigForm
          config={data?.data ?? null}
          isSaving={save.isPending}
          saveError={saveError}
          saveSuccess={saveSuccess}
          onSave={handleSave}
        />
      ) : null}
    </div>
  );
}
