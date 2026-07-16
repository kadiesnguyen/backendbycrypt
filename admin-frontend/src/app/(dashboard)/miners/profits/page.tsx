import { Suspense } from "react";
import { MinerProfitListContainer } from "@/features/miners/profits/MinerProfitListContainer";
import { MinerProfitListSkeleton } from "@/features/miners/profits/MinerProfitListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<MinerProfitListSkeleton />}>
      <MinerProfitListContainer />
    </Suspense>
  );
}
