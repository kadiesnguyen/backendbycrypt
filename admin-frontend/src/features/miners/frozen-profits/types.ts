import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type MinerRow = { id: number; uid?: number; username: string; kjtitle?: string; ktitle?: string; num?: string; coin?: string; status_label?: string; addtime: string; };
export type MinerListParams = ListParams;
export type MinerListResponse = PaginatedResponse<MinerRow>;
