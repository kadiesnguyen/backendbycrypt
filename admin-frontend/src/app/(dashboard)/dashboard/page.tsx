import { Suspense } from "react";
import { DashboardContainer } from "@/features/dashboard/DashboardContainer";
import { DashboardSkeleton } from "@/features/dashboard/DashboardSkeleton";

export default function DashboardPage() {
  return (
    <Suspense fallback={<DashboardSkeleton />}>
      <DashboardContainer />
    </Suspense>
  );
}
