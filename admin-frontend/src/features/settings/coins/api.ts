import { apiClient } from "@/lib/api-client";
import type {
  CoinDetailResponse,
  CoinListParams,
  CoinListResponse,
  CoinMutationResponse,
  CoinStatusResponse,
  CoinStatusType,
  CoinUpsertPayload,
} from "./types";

function toQuery(params: CoinListParams): string {
  const search = new URLSearchParams();
  if (params.page) search.set("page", String(params.page));
  if (params.per_page) search.set("per_page", String(params.per_page));
  if (params.field) search.set("field", params.field);
  if (params.name) search.set("name", params.name);
  if (params.status) search.set("status", String(params.status));
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchCoins(params: CoinListParams = {}): Promise<CoinListResponse> {
  return apiClient<CoinListResponse>(`/coins${toQuery(params)}`);
}

export function fetchCoin(id: number): Promise<CoinDetailResponse> {
  return apiClient<CoinDetailResponse>(`/coins/${id}`);
}

export function createCoin(payload: CoinUpsertPayload): Promise<CoinMutationResponse> {
  return apiClient<CoinMutationResponse>("/coins", {
    method: "POST",
    body: payload,
  });
}

export function updateCoin(
  id: number,
  payload: CoinUpsertPayload,
): Promise<CoinMutationResponse> {
  return apiClient<CoinMutationResponse>(`/coins/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function updateCoinStatus(
  ids: number[],
  type: CoinStatusType,
): Promise<CoinStatusResponse> {
  return apiClient<CoinStatusResponse>("/coins/status", {
    method: "PUT",
    body: { ids, type },
  });
}
