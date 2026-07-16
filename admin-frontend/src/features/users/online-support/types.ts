import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type SupportUser = { id: number; username: string; pending_count: number; };
export type SupportListParams = ListParams;
export type SupportListResponse = PaginatedResponse<SupportUser>;
