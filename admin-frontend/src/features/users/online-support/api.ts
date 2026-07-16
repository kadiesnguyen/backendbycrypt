import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { SupportListParams, SupportListResponse } from "./types";
export function fetchSupportUsers(params: SupportListParams = {}): Promise<SupportListResponse> {
  return apiClient<SupportListResponse>(`/online-support${toQuery({ page: params.page, per_page: params.per_page ?? 50, username: params.username })}`);
}
