"use client";

import { useEffect, useState } from "react";

/** True only after the component has mounted on the client (avoids SSR/localStorage hydration mismatches). */
export function useClientMounted(): boolean {
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  return mounted;
}
