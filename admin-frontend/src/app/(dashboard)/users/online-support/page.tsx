import { Suspense } from "react";
import { OnlineSupportContainer } from "@/features/users/online-support/OnlineSupportContainer";
import { OnlineSupportSkeleton } from "@/features/users/online-support/OnlineSupportSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<OnlineSupportSkeleton />}>
      <OnlineSupportContainer />
    </Suspense>
  );
}
