import { Suspense } from "react";
import { CloseHistoryContainer } from "@/features/trading/close-history/CloseHistoryContainer";
import { CloseHistorySkeleton } from "@/features/trading/close-history/CloseHistorySkeleton";

export default function Page() {
  return (
    <Suspense fallback={<CloseHistorySkeleton />}>
      <CloseHistoryContainer />
    </Suspense>
  );
}
