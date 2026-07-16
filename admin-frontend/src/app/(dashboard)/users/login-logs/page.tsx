import { Suspense } from "react";
import { LoginLogListContainer } from "@/features/users/login-logs/LoginLogListContainer";
import { LoginLogListSkeleton } from "@/features/users/login-logs/LoginLogListSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<LoginLogListSkeleton />}>
      <LoginLogListContainer />
    </Suspense>
  );
}
