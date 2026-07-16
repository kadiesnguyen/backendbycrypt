import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { CtMarketListParams, CtMarketListResponse, CtMarketPayload } from "./types";
export function fetchCtMarkets(params: CtMarketListParams = {}): Promise<CtMarketListResponse> { return apiClient(`/ct-markets${toQuery({ page: params.page, per_page: params.per_page ?? 15 })}`); }
export function updateCtMarket(id: number, payload: CtMarketPayload) { return apiClient(`/ct-markets/${id}`, { method: "PUT", body: payload }); }
export function createCtMarket(payload: CtMarketPayload) { return apiClient("/ct-markets", { method: "POST", body: payload }); }
