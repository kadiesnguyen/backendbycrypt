import { Suspense } from "react";
import { LoanListContainer } from "@/features/finance/loans/LoanListContainer";
import { LoanListSkeleton } from "@/features/finance/loans/LoanListSkeleton";

export default function LoansPage() {
  return (
    <Suspense fallback={<LoanListSkeleton />}>
      <LoanListContainer />
    </Suspense>
  );
}
