import { Suspense } from "react";
import { PlatformMarketListContainer } from "@/features/platform-markets/PlatformMarketListContainer";
import { PlatformMarketListSkeleton } from "@/features/platform-markets/PlatformMarketListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<PlatformMarketListSkeleton />}>
      <PlatformMarketListContainer />
    </Suspense>
  );
}
