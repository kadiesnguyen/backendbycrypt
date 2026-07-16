import { Suspense } from "react";
import { NoticeListContainer } from "@/features/users/notices/NoticeListContainer";
import { NoticeListSkeleton } from "@/features/users/notices/NoticeListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<NoticeListSkeleton />}>
      <NoticeListContainer />
    </Suspense>
  );
}
