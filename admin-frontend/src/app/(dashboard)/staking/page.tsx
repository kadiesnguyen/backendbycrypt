import { Suspense } from "react";
import { StakingPageContainer } from "@/features/staking/StakingPageContainer";
import { StakeListSkeleton } from "@/features/staking/StakingSkeletons";

export default function StakingPage() {
  return (
    <Suspense fallback={<StakeListSkeleton />}>
      <StakingPageContainer />
    </Suspense>
  );
}
