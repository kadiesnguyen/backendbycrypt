import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { SpotListParams, SpotListResponse } from "./types";
export function fetchSpotOrders(params: SpotListParams = {}): Promise<SpotListResponse> {
  return apiClient<SpotListResponse>("/spot-orders/market" + toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username }));
}
