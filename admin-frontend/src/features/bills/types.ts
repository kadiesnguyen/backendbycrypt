import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type Bill = {
  id: number;
  uid: number;
  username: string;
  num: string;
  coinname: string;
  afternum: string;
  type: number;
  st: number;
  st_label: string;
  remark: string | null;
  addtime: string;
};

export type BillListParams = ListParams & {
  coinname?: string;
  st?: number;
};

export type BillListResponse = PaginatedResponse<Bill>;
