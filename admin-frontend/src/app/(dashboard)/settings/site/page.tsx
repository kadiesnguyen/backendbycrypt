import { Suspense } from "react";
import { SiteConfigContainer } from "@/features/settings/site/SiteConfigContainer";
import { SiteConfigSkeleton } from "@/features/settings/site/SiteConfigSkeleton";

export default function SiteSettingsPage() {
  return (
    <Suspense fallback={<SiteConfigSkeleton />}>
      <SiteConfigContainer />
    </Suspense>
  );
}
