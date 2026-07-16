import { Suspense } from "react";
import { WithdrawalListContainer } from "@/features/finance/withdrawals/WithdrawalListContainer";
import { WithdrawalListSkeleton } from "@/features/finance/withdrawals/WithdrawalListSkeleton";

export default function WithdrawalsPage() {
  return (
    <Suspense fallback={<WithdrawalListSkeleton />}>
      <WithdrawalListContainer />
    </Suspense>
  );
}
