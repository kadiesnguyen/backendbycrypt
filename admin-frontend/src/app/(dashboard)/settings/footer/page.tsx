import { Suspense } from "react";
import { FooterListContainer } from "@/features/settings/footer/FooterListContainer";
import { FooterListSkeleton } from "@/features/settings/footer/FooterListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<FooterListSkeleton />}>
      <FooterListContainer />
    </Suspense>
  );
}
