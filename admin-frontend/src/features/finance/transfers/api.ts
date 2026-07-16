import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { TransferListParams, TransferListResponse } from "./types";
export function fetchTransfers(params: TransferListParams = {}): Promise<TransferListResponse> {
  return apiClient<TransferListResponse>(`/transfers${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username })}`);
}
