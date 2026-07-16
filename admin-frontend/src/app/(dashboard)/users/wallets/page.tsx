import { Suspense } from "react";
import { WalletListContainer } from "@/features/users/wallets/WalletListContainer";
import { WalletListSkeleton } from "@/features/users/wallets/WalletListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<WalletListSkeleton />}>
      <WalletListContainer />
    </Suspense>
  );
}
