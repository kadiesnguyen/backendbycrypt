import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type UserWallet = {
  id: number;
  userid: number;
  username: string | null;
  coinname: string;
  name: string;
  addr: string;
  czline: string | null;
  status: number;
  status_label: string;
  addtime: string;
};

export type WalletListParams = ListParams & { coinname?: string };
export type WalletListResponse = PaginatedResponse<UserWallet>;
