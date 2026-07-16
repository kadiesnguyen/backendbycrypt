import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { AdminDetailResponse, AdminListParams, AdminListResponse, AdminUpsertPayload } from "./types";
import type { MutationResponse } from "@/lib/types/api";
export function fetchAdmins(params: AdminListParams = {}): Promise<AdminListResponse> {
  return apiClient<AdminListResponse>(`/admins${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username })}`);
}
export function fetchAdmin(id: number): Promise<AdminDetailResponse> { return apiClient<AdminDetailResponse>(`/admins/${id}`); }
export function createAdmin(payload: AdminUpsertPayload): Promise<MutationResponse> { return apiClient<MutationResponse>("/admins", { method: "POST", body: payload }); }
export function updateAdmin(id: number, payload: AdminUpsertPayload): Promise<MutationResponse> { return apiClient<MutationResponse>(`/admins/${id}`, { method: "PUT", body: payload }); }
export function updateAdminStatus(ids: number[], type: "forbid" | "resume" | "delete"): Promise<MutationResponse> { return apiClient<MutationResponse>("/admins/status", { method: "PUT", body: { ids, type } }); }
