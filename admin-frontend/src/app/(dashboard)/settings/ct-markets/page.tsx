import { Suspense } from "react";
import { CtMarketListContainer } from "@/features/settings/ct-markets/CtMarketListContainer";
import { CtMarketListSkeleton } from "@/features/settings/ct-markets/CtMarketListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<CtMarketListSkeleton />}>
      <CtMarketListContainer />
    </Suspense>
  );
}
