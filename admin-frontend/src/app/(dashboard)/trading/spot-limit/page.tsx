import { Suspense } from "react";
import { SpotLimitListContainer } from "@/features/trading/spot-limit/SpotLimitListContainer";
import { SpotLimitListSkeleton } from "@/features/trading/spot-limit/SpotLimitListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<SpotLimitListSkeleton />}>
      <SpotLimitListContainer />
    </Suspense>
  );
}
