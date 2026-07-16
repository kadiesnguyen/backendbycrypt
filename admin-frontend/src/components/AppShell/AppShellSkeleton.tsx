export function AppShellSkeleton() {
  return (
    <div
      className="flex min-h-screen bg-background"
      aria-busy="true"
      aria-label="Loading admin dashboard"
    >
      <div className="hidden w-80 shrink-0 border-r border-border bg-surface p-4 md:block">
        <div className="skeleton mb-4 h-6 w-24" />
        <div className="space-y-2">
          {Array.from({ length: 5 }).map((_, index) => (
            <div key={index} className="skeleton h-8 w-full" />
          ))}
        </div>
      </div>
      <div className="flex min-w-0 flex-1 flex-col">
        <div className="border-b border-border bg-surface px-4 py-3 md:flex md:h-14 md:items-center md:justify-between md:px-6 md:py-0">
          <div className="flex items-center gap-3">
            <div className="skeleton h-9 w-9 shrink-0 rounded md:hidden" />
            <div className="skeleton h-8 w-32" />
          </div>
          <div className="mt-3 flex flex-wrap justify-end gap-2 border-t border-border pt-3 md:mt-0 md:border-t-0 md:pt-0">
            <div className="skeleton h-8 w-20" />
            <div className="skeleton h-8 w-24" />
          </div>
        </div>
        <div className="flex-1 space-y-4 p-4 md:p-6">
          <div className="skeleton h-8 w-48" />
          <div className="skeleton h-32 w-full" />
          <div className="skeleton h-32 w-full" />
        </div>
      </div>
    </div>
  );
}
