import { Suspense } from "react";
import { SpotSettingsContainer } from "@/features/trading/spot-settings/SpotSettingsContainer";
import { SpotSettingsSkeleton } from "@/features/trading/spot-settings/SpotSettingsSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<SpotSettingsSkeleton />}>
      <SpotSettingsContainer />
    </Suspense>
  );
}
