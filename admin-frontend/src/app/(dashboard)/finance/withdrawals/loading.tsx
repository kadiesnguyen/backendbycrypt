import { WithdrawalListSkeleton } from "@/features/finance/withdrawals/WithdrawalListSkeleton";

export default function WithdrawalsLoading() {
  return (
    <div className="space-y-6">
      <header>
        <div className="skeleton h-8 w-36 rounded bg-surface-elevated" />
        <div className="skeleton mt-2 h-4 w-72 rounded bg-surface-elevated" />
      </header>
      <WithdrawalListSkeleton />
    </div>
  );
}
