import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type ContractOrder = {
  id: number;
  uid: number;
  username: string;
  coinname: string;
  num: string;
  status: number;
  status_label: string;
  is_win: number;
  is_win_label: string;
  buytime?: string;
  selltime?: string;
  addtime?: string;
  balance_before?: number | null;
  balance_after?: number | null;
  profit_loss?: number | null;
};

export type OrderListParams = ListParams;
export type OrderListResponse = PaginatedResponse<ContractOrder>;
