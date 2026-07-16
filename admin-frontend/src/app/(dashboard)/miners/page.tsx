import { Suspense } from "react";
import { MinerListContainer } from "@/features/miners/MinerListContainer";
import { MinerListSkeleton } from "@/features/miners/MinerListSkeleton";

export default function MinersPage() {
  return (
    <Suspense fallback={<MinerListSkeleton />}>
      <MinerListContainer />
    </Suspense>
  );
}
