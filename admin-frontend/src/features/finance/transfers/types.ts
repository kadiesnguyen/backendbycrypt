import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type Transfer = { id: number; userid: number; username: string; from_coin: string; to_coin: string; from_amount: string; to_amount: string; convert_rate: string; addtime: string; status: number; status_label: string; };
export type TransferListParams = ListParams;
export type TransferListResponse = PaginatedResponse<Transfer>;
