import type { ListParams, PaginatedResponse, MutationResponse } from "@/lib/types/api";

export type Notice = {
  id: number;
  uid: number;
  account: string;
  title: string;
  content: string;
  addtime: string;
  status: number;
  user_view?: number;
};
export type NoticeListParams = ListParams & { account?: string; username?: string };
export type NoticeListResponse = PaginatedResponse<Notice>;
export type NoticePayload = { user_id?: number; title: string; content: string; type: 1 | 2; imgs?: string };
