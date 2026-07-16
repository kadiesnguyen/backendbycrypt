import { apiClient } from "@/lib/api-client";
import type {
  ActionResponse,
  ContractOrdersListParams,
  ContractOrdersListResponse,
  SetWinLossPayload,
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

export function fetchContractOrders(
  params: ContractOrdersListParams = {},
): Promise<ContractOrdersListResponse> {
  return apiClient<ContractOrdersListResponse>(`/contract-orders${toQuery(params)}`);
}

export function setContractWinLoss(payload: SetWinLossPayload): Promise<ActionResponse> {
  return apiClient<ActionResponse>("/contract-orders/win-loss", {
    method: "PUT",
    body: payload,
  });
}

export function settleContractOrder(id: number): Promise<ActionResponse> {
  return apiClient<ActionResponse>(`/contract-orders/${id}/settle`, {
    method: "POST",
  });
}

export function settleStuckContractOrders(): Promise<ActionResponse & { data?: { settled: number; failed: number; due: number } }> {
  return apiClient(`/contract-orders/settle-stuck`, { method: "POST" });
}
