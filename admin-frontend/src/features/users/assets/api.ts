import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { DetailResponse, MutationResponse } from "@/lib/types/api";
import type { AssetListParams, AssetListResponse, UserAsset } from "./types";

export function fetchAssets(params: AssetListParams = {}): Promise<AssetListResponse> {
  return apiClient<AssetListResponse>(
    `/user-assets${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username })}`,
  );
}

export function fetchAsset(id: number): Promise<DetailResponse<UserAsset>> {
  return apiClient<DetailResponse<UserAsset>>(`/user-assets/${id}`);
}

export function updateAsset(id: number, balances: Record<string, string | number>): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/user-assets/${id}`, { method: "PUT", body: { balances } });
}
