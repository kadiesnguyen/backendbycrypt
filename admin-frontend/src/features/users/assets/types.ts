import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type UserAsset = {
  id: number;
  userid: number;
  username: string | null;
  usdt: string;
  btc: string;
  eth: string;
};

export type AssetListParams = ListParams;
export type AssetListResponse = PaginatedResponse<UserAsset>;
