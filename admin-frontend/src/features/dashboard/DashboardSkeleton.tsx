export function DashboardSkeleton() {
  return (
    <div className="flex min-w-0 flex-col gap-4 overflow-x-hidden md:gap-6">
      <div className="flex items-center justify-between gap-4">
        <div className="min-w-0 space-y-2">
          <div className="skeleton h-5 w-36" />
          <div className="skeleton h-4 w-56 max-w-full" />
        </div>
        <div className="skeleton h-8 w-24 rounded-lg" />
      </div>

      <div className="skeleton h-48 rounded-lg" />

      <div className="grid grid-cols-12 gap-4 md:gap-6">
        {Array.from({ length: 6 }).map((_, i) => (
          <div key={i} className="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-2">
            <div className="skeleton h-[5.5rem] rounded-lg" />
          </div>
        ))}
      </div>

      <div className="grid grid-cols-12 gap-4 md:gap-6">
        <div className="col-span-12 lg:col-span-8">
          <div className="skeleton h-[260px] rounded-lg" />
        </div>
        <div className="col-span-12 lg:col-span-4">
          <div className="skeleton h-[260px] rounded-lg" />
        </div>
      </div>

      <div className="grid grid-cols-12 gap-4 md:gap-6">
        {Array.from({ length: 2 }).map((_, i) => (
          <div key={i} className="col-span-12 lg:col-span-6">
            <div className="skeleton h-[15.5rem] rounded-lg" />
          </div>
        ))}
      </div>

      <div className="grid grid-cols-12 gap-4 md:gap-6">
        {Array.from({ length: 2 }).map((_, i) => (
          <div key={i} className="col-span-12 xl:col-span-6">
            <div className="skeleton h-[12.5rem] rounded-lg" />
          </div>
        ))}
      </div>
    </div>
  );
}
