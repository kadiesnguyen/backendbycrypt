"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchDashboardOverview } from "./api";

export const dashboardQueryKey = ["admin", "dashboard", "overview"] as const;

export function useDashboard() {
  return useQuery({
    queryKey: dashboardQueryKey,
    queryFn: fetchDashboardOverview,
    select: (response) => response.data,
    refetchInterval: 60_000,
  });
}
