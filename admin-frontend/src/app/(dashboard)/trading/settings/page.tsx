import { Suspense } from "react";
import { ContractSettingsContainer } from "@/features/trading/settings/ContractSettingsContainer";
import { ContractSettingsSkeleton } from "@/features/trading/settings/ContractSettingsSkeleton";

export default function ContractSettingsPage() {
  return (
    <Suspense fallback={<ContractSettingsSkeleton />}>
      <ContractSettingsContainer />
    </Suspense>
  );
}
