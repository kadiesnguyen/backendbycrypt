"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchPendingCounts } from "./api";

export function usePendingCounts() {
  return useQuery({
    queryKey: ["admin", "pending-counts"],
    queryFn: fetchPendingCounts,
    refetchInterval: 5_000,
    refetchIntervalInBackground: true,
    staleTime: 0,
  });
}
