import { DepositPortListSkeleton } from "@/features/settings/deposit-ports/DepositPortListSkeleton";

export default function DepositPortsSettingsLoading() {
  return (
    <div className="space-y-6">
      <header>
        <div className="skeleton h-8 w-40 rounded bg-surface-elevated" />
        <div className="skeleton mt-2 h-4 w-72 rounded bg-surface-elevated" />
      </header>
      <DepositPortListSkeleton />
    </div>
  );
}
