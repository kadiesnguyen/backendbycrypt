import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { DetailResponse, MutationResponse } from "@/lib/types/api";
import type { UserWallet, WalletListParams, WalletListResponse } from "./types";

export type WalletUpsertPayload = {
  userid?: number;
  username?: string;
  name: string;
  addr: string;
  czline?: string;
  status?: number;
  addtime?: string;
};

export function fetchWallets(params: WalletListParams = {}): Promise<WalletListResponse> {
  return apiClient<WalletListResponse>(
    `/user-wallets${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username, coinname: params.coinname })}`,
  );
}

export function fetchWallet(id: number): Promise<DetailResponse<UserWallet>> {
  return apiClient<DetailResponse<UserWallet>>(`/user-wallets/${id}`);
}

export function createWallet(payload: WalletUpsertPayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/user-wallets", { method: "POST", body: payload });
}

export function updateWallet(id: number, payload: WalletUpsertPayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/user-wallets/${id}`, { method: "PUT", body: payload });
}

export function deleteWallets(ids: number[]): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/user-wallets", { method: "DELETE", body: { ids } });
}
