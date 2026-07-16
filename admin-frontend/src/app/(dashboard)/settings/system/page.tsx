import { Suspense } from "react";
import { SystemParamsContainer } from "@/features/settings/system/SystemParamsContainer";
import { SystemParamsSkeleton } from "@/features/settings/system/SystemParamsSkeleton";

export default function Page() {
  return (
    <Suspense fallback={<SystemParamsSkeleton />}>
      <SystemParamsContainer />
    </Suspense>
  );
}
