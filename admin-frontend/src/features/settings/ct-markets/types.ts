import type { DetailResponse, ListParams, PaginatedResponse } from "@/lib/types/api";
export type CtMarket = { id: number; coinname: string; name: string; symbol: string; title: string; status: number; sort: number; };
export type CtMarketListParams = ListParams;
export type CtMarketListResponse = PaginatedResponse<CtMarket>;
export type CtMarketPayload = Partial<CtMarket>;
