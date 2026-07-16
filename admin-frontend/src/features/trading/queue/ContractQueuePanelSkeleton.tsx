export function ContractQueuePanelSkeleton() {
  return (
    <div className="space-y-3" aria-busy="true" aria-label="Loading result queue">
      <div className="skeleton h-24 rounded-lg bg-surface-elevated" />
      <div className="skeleton h-48 rounded-lg bg-surface-elevated" />
    </div>
  );
}
