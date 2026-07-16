"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchPendingCounts } from "./api";

export function usePendingCounts() {
  return useQuery({
    queryKey: ["admin", "pending-counts"],
    queryFn: fetchPendingCounts,
    refetchInterval: 30_000,
    staleTime: 15_000,
  });
}
