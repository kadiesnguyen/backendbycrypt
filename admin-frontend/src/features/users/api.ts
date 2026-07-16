import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { DetailResponse, MutationResponse } from "@/lib/types/api";
import type { AdminUser, KycFormData, UsersListParams, UsersListResponse } from "./types";

export type UserUpsertPayload = {
  username?: string;
  password?: string;
  paypassword?: string;
  fullname?: string;
  phonenumber?: string;
  cccd?: string;
  status?: number;
  txstate?: number;
  bank_name?: string;
  bank_acc_no?: string;
  bank_acc_name?: string;
  wallet?: string;
  invit?: string;
  invit_1?: number;
  invit_2?: number;
  invit_3?: number;
  hy_result_mode?: number;
  kefu?: number | string;
};

export type UserStatusType = 1 | 2 | 3 | 4 | 5;

export function fetchUsers(params: UsersListParams = {}): Promise<UsersListResponse> {
  return apiClient<UsersListResponse>(
    `/users${toQuery({
      page: params.page,
      per_page: params.per_page ?? 15,
      username: params.username,
      status: params.status,
    })}`,
  );
}

export function fetchUser(id: number): Promise<DetailResponse<AdminUser>> {
  return apiClient<DetailResponse<AdminUser>>(`/users/${id}`);
}

export function createUser(payload: UserUpsertPayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/users", { method: "POST", body: payload });
}

export function updateUser(id: number, payload: UserUpsertPayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}`, { method: "PUT", body: payload });
}

export function updateUsersStatus(ids: number[], type: UserStatusType): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/users/status", { method: "PUT", body: { ids, type } });
}

export function updateUserTradeLock(
  id: number,
  payload: { locked: boolean; message?: string },
): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}/trade-lock`, { method: "PUT", body: payload });
}

export function addUserFunds(id: number, amount: string): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}/add-funds`, { method: "POST", body: { amount } });
}

export function reviewUserKyc(
  id: number,
  payload: { rzstatus: 2 | 3; username: string; kjid?: number },
): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}/kyc-review`, { method: "POST", body: payload });
}

export function fetchKycForm(id: number): Promise<DetailResponse<KycFormData>> {
  return apiClient<DetailResponse<KycFormData>>(`/users/${id}/kyc-form`);
}

export function setUserAgent(id: number): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}/agent`, { method: "PUT" });
}

export function cancelUserAgent(id: number): Promise<MutationResponse> {
  return apiClient<MutationResponse>(`/users/${id}/agent`, { method: "DELETE" });
}
