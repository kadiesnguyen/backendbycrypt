import { apiClient } from "@/lib/api-client";
import type {
  WithdrawalActionResponse,
  WithdrawalsListParams,
  WithdrawalsListResponse,
} from "./types";

function toQuery(params: WithdrawalsListParams): string {
  const search = new URLSearchParams();
  if (params.page) search.set("page", String(params.page));
  if (params.per_page) search.set("per_page", String(params.per_page));
  if (params.username) search.set("username", params.username);
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchWithdrawals(
  params: WithdrawalsListParams = {},
): Promise<WithdrawalsListResponse> {
  return apiClient<WithdrawalsListResponse>(`/withdrawals${toQuery(params)}`);
}

export function approveWithdrawal(id: number): Promise<WithdrawalActionResponse> {
  return apiClient<WithdrawalActionResponse>(`/withdrawals/${id}/approve`, { method: "POST" });
}

export function rejectWithdrawal(id: number): Promise<WithdrawalActionResponse> {
  return apiClient<WithdrawalActionResponse>(`/withdrawals/${id}/reject`, { method: "POST" });
}

export function deleteWithdrawal(id: number): Promise<WithdrawalActionResponse> {
  return apiClient<WithdrawalActionResponse>(`/withdrawals/${id}`, { method: "DELETE" });
}
