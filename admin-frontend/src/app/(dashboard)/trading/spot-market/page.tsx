import { Suspense } from "react";
import { SpotMarketListContainer } from "@/features/trading/spot-market/SpotMarketListContainer";
import { SpotMarketListSkeleton } from "@/features/trading/spot-market/SpotMarketListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<SpotMarketListSkeleton />}>
      <SpotMarketListContainer />
    </Suspense>
  );
}
