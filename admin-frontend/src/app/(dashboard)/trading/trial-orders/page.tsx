import { Suspense } from "react";
import { TrialOrderListContainer } from "@/features/trading/trial-orders/TrialOrderListContainer";
import { TrialOrderListSkeleton } from "@/features/trading/trial-orders/TrialOrderListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<TrialOrderListSkeleton />}>
      <TrialOrderListContainer />
    </Suspense>
  );
}
