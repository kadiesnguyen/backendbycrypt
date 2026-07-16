import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { DetailResponse, MutationResponse } from "@/lib/types/api";
import type { LoginLog, LoginLogListParams, LoginLogListResponse } from "./types";

export type LoginLogStatusType = "forbid" | "resume" | "repeal" | "delete" | "del";

export type LoginLogUpdatePayload = {
  type?: string;
  remark?: string;
  addip?: string;
  addr?: string;
  status?: number;
  addtime?: string;
};

export function fetchLoginLogs(params: LoginLogListParams = {}): Promise<LoginLogListResponse> {
  return apiClient<LoginLogListResponse>(
    `/user-login-logs${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username, status: params.status })}`,
  );
}

export function fetchLoginLog(id: number): Promise<DetailResponse<LoginLog>> {
  return apiClient<DetailResponse<LoginLog>>(`/user-login-logs/${id}`);
}

export function updateLoginLog(id: number, payload: LoginLogUpdatePayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/user-login-logs/${id}`, { method: "PUT", body: payload });
}

export function updateLoginLogStatus(ids: number[], type: LoginLogStatusType): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/user-login-logs/status", { method: "PUT", body: { ids, type } });
}
