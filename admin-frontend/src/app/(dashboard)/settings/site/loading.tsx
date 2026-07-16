import { SiteConfigSkeleton } from "@/features/settings/site/SiteConfigSkeleton";

export default function SiteSettingsLoading() {
  return (
    <div className="space-y-6">
      <header>
        <div className="skeleton h-8 w-48 rounded bg-surface-elevated" />
        <div className="skeleton mt-2 h-4 w-72 rounded bg-surface-elevated" />
      </header>
      <SiteConfigSkeleton />
    </div>
  );
}
