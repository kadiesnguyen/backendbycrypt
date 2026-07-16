import { Suspense } from "react";
import { ActiveMinerListContainer } from "@/features/miners/active/ActiveMinerListContainer";
import { ActiveMinerListSkeleton } from "@/features/miners/active/ActiveMinerListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<ActiveMinerListSkeleton />}>
      <ActiveMinerListContainer />
    </Suspense>
  );
}
