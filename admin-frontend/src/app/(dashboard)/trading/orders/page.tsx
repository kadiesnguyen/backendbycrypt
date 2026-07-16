import { Suspense } from "react";
import { ContractOrderListContainer } from "@/features/trading/orders/ContractOrderListContainer";
import { ContractOrderListSkeleton } from "@/features/trading/orders/ContractOrderListSkeleton";

export default function ContractOrdersPage() {
  return (
    <Suspense fallback={<ContractOrderListSkeleton />}>
      <ContractOrderListContainer />
    </Suspense>
  );
}
