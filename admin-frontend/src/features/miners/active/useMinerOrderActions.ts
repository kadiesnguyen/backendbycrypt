import { apiClient } from "@/lib/api-client";
import type { MutationResponse } from "@/lib/types/api";

export function updateMinerOrderStatus(ids: number[], type: 1 | 2 | 3): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/miner-orders/status", { method: "PUT", body: { ids, type } });
}
