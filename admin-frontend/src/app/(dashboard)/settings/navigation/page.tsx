import { Suspense } from "react";
import { NavigationListContainer } from "@/features/settings/navigation/NavigationListContainer";
import { NavigationListSkeleton } from "@/features/settings/navigation/NavigationListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<NavigationListSkeleton />}>
      <NavigationListContainer />
    </Suspense>
  );
}
