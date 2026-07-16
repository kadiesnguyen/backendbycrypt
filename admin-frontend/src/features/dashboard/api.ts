import { apiClient } from "@/lib/api-client";
import type { DashboardOverviewResponse } from "./types";

export function fetchDashboardOverview(): Promise<DashboardOverviewResponse> {
  return apiClient<DashboardOverviewResponse>("/dashboard/stats");
}
