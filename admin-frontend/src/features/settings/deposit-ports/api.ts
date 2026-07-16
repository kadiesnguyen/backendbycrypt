import { apiClient } from "@/lib/api-client";
import type {
  DepositPortDetailResponse,
  DepositPortFormMetaResponse,
  DepositPortListResponse,
  DepositPortMutationResponse,
  DepositPortStatusResponse,
  DepositPortStatusType,
  DepositPortUpsertPayload,
} from "./types";

export function fetchDepositPorts(): Promise<DepositPortListResponse> {
  return apiClient<DepositPortListResponse>("/deposit-ports");
}

export function fetchDepositPortFormMeta(): Promise<DepositPortFormMetaResponse> {
  return apiClient<DepositPortFormMetaResponse>("/deposit-ports/form-meta");
}

export function fetchDepositPort(id: number): Promise<DepositPortDetailResponse> {
  return apiClient<DepositPortDetailResponse>(`/deposit-ports/${id}`);
}

export function createDepositPort(
  payload: DepositPortUpsertPayload,
): Promise<DepositPortMutationResponse> {
  return apiClient<DepositPortMutationResponse>("/deposit-ports", {
    method: "POST",
    body: payload,
  });
}

export function updateDepositPort(
  id: number,
  payload: DepositPortUpsertPayload,
): Promise<DepositPortMutationResponse> {
  return apiClient<DepositPortMutationResponse>(`/deposit-ports/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function updateDepositPortStatus(
  ids: number[],
  type: DepositPortStatusType,
): Promise<DepositPortStatusResponse> {
  return apiClient<DepositPortStatusResponse>("/deposit-ports/status", {
    method: "PUT",
    body: { ids, type },
  });
}
