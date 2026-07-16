import { Suspense } from "react";
import { AdminListContainer } from "@/features/users/admins/AdminListContainer";
import { AdminListSkeleton } from "@/features/users/admins/AdminListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<AdminListSkeleton />}>
      <AdminListContainer />
    </Suspense>
  );
}
