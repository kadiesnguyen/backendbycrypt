import { MinerListSkeleton } from "@/features/miners/MinerListSkeleton";

export default function MinersLoading() {
  return (
    <div className="space-y-6">
      <header>
        <div className="skeleton h-8 w-32 rounded bg-surface-elevated" />
        <div className="skeleton mt-2 h-4 w-64 rounded bg-surface-elevated" />
      </header>
      <MinerListSkeleton />
    </div>
  );
}
