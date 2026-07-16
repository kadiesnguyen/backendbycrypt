import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type LoginLog = {
  id: number;
  userid: number;
  username: string | null;
  type: string | null;
  remark: string | null;
  addip: string | null;
  addr: string | null;
  addtime: string;
  status: number;
  status_label: string;
};

export type LoginLogListParams = ListParams & { status?: number };
export type LoginLogListResponse = PaginatedResponse<LoginLog>;
