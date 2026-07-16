import { apiClient } from "@/lib/api-client";

export type PendingCounts = {
  deposits: number;
  withdrawals: number;
  contract_orders: number;
};

export type PendingCountsResponse = {
  status: boolean;
  data: PendingCounts;
};

export function fetchPendingCounts(): Promise<PendingCountsResponse> {
  return apiClient<PendingCountsResponse>("/pending-counts");
}
