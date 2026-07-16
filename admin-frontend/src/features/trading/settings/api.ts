import { apiClient } from "@/lib/api-client";
import type {
  ContractSettingResponse,
  ContractSettingUpdateResponse,
  MarketDetailResponse,
  MarketFormMetaResponse,
  MarketMutationResponse,
  MarketsListParams,
  MarketsListResponse,
  UpdateContractSettingPayload,
  UpsertMarketPayload,
} from "./types";

function toQuery(params: Record<string, string | number | undefined>): string {
  const search = new URLSearchParams();
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== "") {
      search.set(key, String(value));
    }
  }
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchContractSettings(): Promise<ContractSettingResponse> {
  return apiClient<ContractSettingResponse>("/contract-settings");
}

export function updateContractSettings(
  payload: UpdateContractSettingPayload,
): Promise<ContractSettingUpdateResponse> {
  return apiClient<ContractSettingUpdateResponse>("/contract-settings", {
    method: "PUT",
    body: payload,
  });
}

export function fetchContractMarkets(params: MarketsListParams = {}): Promise<MarketsListResponse> {
  return apiClient<MarketsListResponse>(`/contract-markets${toQuery(params)}`);
}

export function fetchMarketFormMeta(): Promise<MarketFormMetaResponse> {
  return apiClient<MarketFormMetaResponse>("/contract-markets/form-meta");
}

export function fetchContractMarket(id: number): Promise<MarketDetailResponse> {
  return apiClient<MarketDetailResponse>(`/contract-markets/${id}`);
}

export function createContractMarket(payload: UpsertMarketPayload): Promise<MarketMutationResponse> {
  return apiClient<MarketMutationResponse>("/contract-markets", {
    method: "POST",
    body: payload,
  });
}

export function updateContractMarket(
  id: number,
  payload: UpsertMarketPayload,
): Promise<MarketMutationResponse> {
  return apiClient<MarketMutationResponse>(`/contract-markets/${id}`, {
    method: "PUT",
    body: payload,
  });
}
