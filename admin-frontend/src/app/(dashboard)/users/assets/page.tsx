import { Suspense } from "react";
import { AssetListContainer } from "@/features/users/assets/AssetListContainer";
import { AssetListSkeleton } from "@/features/users/assets/AssetListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<AssetListSkeleton />}>
      <AssetListContainer />
    </Suspense>
  );
}
