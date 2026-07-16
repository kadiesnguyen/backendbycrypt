export function SiteConfigSkeleton() {
  return (
    <div className="space-y-4" aria-busy="true" aria-label="Loading site configuration">
      {Array.from({ length: 4 }).map((_, i) => (
        <div key={i} className="skeleton h-32 rounded-lg bg-surface-elevated" />
      ))}
    </div>
  );
}
