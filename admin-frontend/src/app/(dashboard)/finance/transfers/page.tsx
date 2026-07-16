import { Suspense } from "react";
import { TransferListContainer } from "@/features/finance/transfers/TransferListContainer";
import { TransferListSkeleton } from "@/features/finance/transfers/TransferListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<TransferListSkeleton />}>
      <TransferListContainer />
    </Suspense>
  );
}
