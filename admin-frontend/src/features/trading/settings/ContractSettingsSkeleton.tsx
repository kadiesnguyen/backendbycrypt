export function ContractSettingsSkeleton() {
  return (
    <div className="space-y-6" aria-busy="true" aria-label="Loading contract settings">
      <div className="skeleton h-64 rounded-lg bg-surface-elevated" />
      <div className="skeleton h-48 rounded-lg bg-surface-elevated" />
    </div>
  );
}
