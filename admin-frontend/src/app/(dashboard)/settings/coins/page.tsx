import { Suspense } from "react";
import { CoinListContainer } from "@/features/settings/coins/CoinListContainer";
import { CoinListSkeleton } from "@/features/settings/coins/CoinListSkeleton";

export default function CoinsSettingsPage() {
  return (
    <Suspense fallback={<CoinListSkeleton />}>
      <CoinListContainer />
    </Suspense>
  );
}
