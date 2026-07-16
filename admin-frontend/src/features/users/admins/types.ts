import type { DetailResponse, ListParams, PaginatedResponse, MutationResponse } from "@/lib/types/api";
export type AdminAccount = { id: number; email: string; username: string; nickname: string; moble: string; sort: number; status: number; level: number; addtime: number; auth_group?: number; };
export type AdminListParams = ListParams;
export type AdminListResponse = PaginatedResponse<AdminAccount>;
export type AdminDetailResponse = DetailResponse<AdminAccount>;
export type AdminUpsertPayload = { email?: string; username: string; nickname?: string; moble?: string; password?: string; status?: number; level?: number; sort?: number; auth_group?: number; };
