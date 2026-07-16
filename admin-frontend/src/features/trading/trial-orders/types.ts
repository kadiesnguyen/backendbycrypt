import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type ContractOrder = { id: number; uid: number; username: string; coinname: string; num: string; status: number; status_label: string; buytime?: string; addtime?: string; };
export type OrderListParams = ListParams;
export type OrderListResponse = PaginatedResponse<ContractOrder>;
