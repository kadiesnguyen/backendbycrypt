import { Suspense } from "react";
import { DepositPortListContainer } from "@/features/settings/deposit-ports/DepositPortListContainer";
import { DepositPortListSkeleton } from "@/features/settings/deposit-ports/DepositPortListSkeleton";

export default function DepositPortsSettingsPage() {
  return (
    <Suspense fallback={<DepositPortListSkeleton />}>
      <DepositPortListContainer />
    </Suspense>
  );
}
