export function StakeListSkeleton() {
  return (
    <div className="space-y-3" aria-busy="true" aria-label="Loading staking packages">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="skeleton h-12 rounded-lg bg-surface-elevated" />
      ))}
    </div>
  );
}

export function StakingLogListSkeleton() {
  return (
    <div className="space-y-3" aria-busy="true" aria-label="Loading staking logs">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="skeleton h-12 rounded-lg bg-surface-elevated" />
      ))}
    </div>
  );
}
