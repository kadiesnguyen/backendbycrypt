import { StakeListSkeleton } from "@/features/staking/StakingSkeletons";

export default function StakingLoading() {
  return (
    <div className="space-y-6">
      <header>
        <div className="skeleton h-8 w-32 rounded bg-surface-elevated" />
        <div className="skeleton mt-2 h-4 w-64 rounded bg-surface-elevated" />
      </header>
      <StakeListSkeleton />
    </div>
  );
}
