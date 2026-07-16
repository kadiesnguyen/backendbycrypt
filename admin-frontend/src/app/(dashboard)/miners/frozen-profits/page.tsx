import { Suspense } from "react";
import { FrozenProfitListContainer } from "@/features/miners/frozen-profits/FrozenProfitListContainer";
import { FrozenProfitListSkeleton } from "@/features/miners/frozen-profits/FrozenProfitListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<FrozenProfitListSkeleton />}>
      <FrozenProfitListContainer />
    </Suspense>
  );
}
