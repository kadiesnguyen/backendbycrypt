import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type {
  LoanActionResponse,
  LoanSettingsResponse,
  LoanSettingsUpdatePayload,
  LoansListParams,
  LoansListResponse,
} from "./types";

export function fetchLoans(params: LoansListParams = {}): Promise<LoansListResponse> {
  return apiClient<LoansListResponse>(
    `/loans${toQuery({
      page: params.page,
      per_page: params.per_page ?? 15,
      username: params.username,
      status: params.status,
    })}`,
  );
}

export function approveLoan(id: number, note?: string): Promise<LoanActionResponse> {
  return apiClient<LoanActionResponse>(`/loans/${id}/approve`, {
    method: "POST",
    body: note ? { note } : {},
  });
}

export function rejectLoan(id: number, note?: string): Promise<LoanActionResponse> {
  return apiClient<LoanActionResponse>(`/loans/${id}/reject`, {
    method: "POST",
    body: note ? { note } : {},
  });
}

export function fetchLoanSettings(): Promise<LoanSettingsResponse> {
  return apiClient<LoanSettingsResponse>("/loan-settings");
}

export function updateLoanSettings(
  payload: LoanSettingsUpdatePayload,
): Promise<LoanSettingsResponse> {
  return apiClient<LoanSettingsResponse>("/loan-settings", {
    method: "PUT",
    body: payload,
  });
}
