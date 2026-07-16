import type { DetailResponse, ListParams, PaginatedResponse } from "@/lib/types/api";
export type PlatformMarket = { id: number; name: string; status: number; sort: number; new_price: string; buy_price: string; sell_price: string; volume: string; change: string; trade: number; };
export type PlatformMarketListParams = ListParams;
export type PlatformMarketListResponse = PaginatedResponse<PlatformMarket>;
export type PlatformMarketDetailResponse = DetailResponse<PlatformMarket>;
export type PlatformMarketPayload = Partial<PlatformMarket>;
