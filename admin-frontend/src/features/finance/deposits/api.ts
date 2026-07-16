import { apiClient } from "@/lib/api-client";
import type {
  DepositActionResponse,
  DepositsListParams,
  DepositsListResponse,
} from "./types";

function toQuery(params: DepositsListParams): string {
  const search = new URLSearchParams();
  if (params.page) search.set("page", String(params.page));
  if (params.per_page) search.set("per_page", String(params.per_page));
  if (params.username) search.set("username", params.username);
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchDeposits(params: DepositsListParams = {}): Promise<DepositsListResponse> {
  return apiClient<DepositsListResponse>(`/deposits${toQuery(params)}`);
}

export function approveDeposit(id: number): Promise<DepositActionResponse> {
  return apiClient<DepositActionResponse>(`/deposits/${id}/approve`, { method: "POST" });
}

export function rejectDeposit(id: number): Promise<DepositActionResponse> {
  return apiClient<DepositActionResponse>(`/deposits/${id}/reject`, { method: "POST" });
}

export function deleteDeposit(id: number): Promise<DepositActionResponse> {
  return apiClient<DepositActionResponse>(`/deposits/${id}`, { method: "DELETE" });
}
