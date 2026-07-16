import type { ListParams, PaginatedResponse } from "@/lib/types/api";
export type SpotOrder = { id: number; uid: number; account: string; symbol: string; coin: string; coinnum: string; usdtnum: string; price: string; status: number; status_label: string; addtime: string; type_label: string; ordertype_label: string; };
export type SpotListParams = ListParams;
export type SpotListResponse = PaginatedResponse<SpotOrder>;
