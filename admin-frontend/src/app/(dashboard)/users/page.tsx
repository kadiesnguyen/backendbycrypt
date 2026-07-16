import { Suspense } from "react";
import { UserListContainer } from "@/features/users/UserListContainer";
import { UserListSkeleton } from "@/features/users/UserListSkeleton";

export default function UsersPage() {
  return (
    <Suspense fallback={<UserListSkeleton />}>
      <UserListContainer />
    </Suspense>
  );
}
