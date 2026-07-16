export function WithdrawalListSkeleton() {
  return (
    <div className="space-y-3" aria-busy="true" aria-label="Loading withdrawals">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="skeleton h-12 rounded-lg bg-surface-elevated" />
      ))}
    </div>
  );
}
