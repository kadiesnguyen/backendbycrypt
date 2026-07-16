import { Suspense } from "react";
import { BillListContainer } from "@/features/bills/BillListContainer";
import { BillListSkeleton } from "@/features/bills/BillListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<BillListSkeleton />}>
      <BillListContainer />
    </Suspense>
  );
}
