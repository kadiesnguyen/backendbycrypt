import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type NavItem = { id: number; lang: string; name: string; title: string; url: string; sort: number; status: number; };
export type NavListParams = ListParams;
export type NavListResponse = PaginatedResponse<NavItem>;
export type NavPayload = { lang?: string; name: string; title: string; url: string; sort?: number; status?: number; };
