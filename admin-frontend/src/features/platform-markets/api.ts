import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { PlatformMarketDetailResponse, PlatformMarketListParams, PlatformMarketListResponse, PlatformMarketPayload } from "./types";
export function fetchPlatformMarkets(params: PlatformMarketListParams = {}): Promise<PlatformMarketListResponse> {
  return apiClient<PlatformMarketListResponse>(`/platform-markets${toQuery({ page: params.page, per_page: params.per_page ?? 15 })}`);
}
export function fetchPlatformMarket(id: number): Promise<PlatformMarketDetailResponse> { return apiClient(`/platform-markets/${id}`); }
export function updatePlatformMarket(id: number, payload: PlatformMarketPayload) { return apiClient(`/platform-markets/${id}`, { method: "PUT", body: payload }); }
export function createPlatformMarket(payload: PlatformMarketPayload) { return apiClient("/platform-markets", { method: "POST", body: payload }); }
