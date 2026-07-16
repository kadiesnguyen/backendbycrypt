import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { MinerListParams, MinerListResponse } from "./types";
export function fetchMinerRows(params: MinerListParams = {}): Promise<MinerListResponse> {
  return apiClient<MinerListResponse>("/miner-profits" + toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username }));
}
