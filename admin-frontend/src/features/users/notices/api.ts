import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { MutationResponse } from "@/lib/types/api";
import type { NoticeListParams, NoticeListResponse, NoticePayload } from "./types";

export function fetchNotices(params: NoticeListParams = {}): Promise<NoticeListResponse> {
  return apiClient<NoticeListResponse>(
    `/notices${toQuery({
      page: params.page,
      per_page: params.per_page ?? 15,
      account: params.account ?? params.username,
    })}`,
  );
}

export function sendNotice(payload: NoticePayload): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/notices", { method: "POST", body: payload });
}

export function deleteNotices(ids: number[]): Promise<MutationResponse> {
  return apiClient<MutationResponse>("/notices", { method: "DELETE", body: { ids } });
}
