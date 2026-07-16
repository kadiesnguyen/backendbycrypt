export function DepositPortListSkeleton() {
  return (
    <div className="space-y-3" aria-busy="true" aria-label="Loading deposit ports">
      {Array.from({ length: 5 }).map((_, i) => (
        <div key={i} className="skeleton h-12 rounded-lg bg-surface-elevated" />
      ))}
    </div>
  );
}
