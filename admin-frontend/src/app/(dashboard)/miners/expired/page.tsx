import { Suspense } from "react";
import { ExpiredMinerListContainer } from "@/features/miners/expired/ExpiredMinerListContainer";
import { ExpiredMinerListSkeleton } from "@/features/miners/expired/ExpiredMinerListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<ExpiredMinerListSkeleton />}>
      <ExpiredMinerListContainer />
    </Suspense>
  );
}
