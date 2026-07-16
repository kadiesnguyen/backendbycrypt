import { Suspense } from "react";
import { DepositListContainer } from "@/features/finance/deposits/DepositListContainer";
import { DepositListSkeleton } from "@/features/finance/deposits/DepositListSkeleton";

export default function DepositsPage() {
  return (
    <Suspense fallback={<DepositListSkeleton />}>
      <DepositListContainer />
    </Suspense>
  );
}
