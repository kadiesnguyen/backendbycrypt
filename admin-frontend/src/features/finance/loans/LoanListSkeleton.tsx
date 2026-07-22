export function LoanListSkeleton() {
  return (
    <div className="animate-pulse space-y-3 rounded-lg border border-border bg-surface p-4">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="h-10 rounded bg-surface-elevated" />
      ))}
    </div>
  );
}
