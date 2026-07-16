export type AdminCoin = {
  id: number;
  name: string;
  title: string | null;
  type: number;
  czline: string | null;
  czaddress: string | null;
  czstatus: number;
  czminnum: string | null;
  txstatus: number;
  txminnum: string | null;
  txmaxnum: string | null;
  sxftype: number;
  txsxf: string | null;
  txsxf_n: string | null;
  bbsxf: string | null;
  hysxf: string | null;
  bank: string | null;
  sort: number;
  status: number;
  addtime: string | null;
};

export type CoinListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type CoinListResponse = {
  status: boolean;
  data: AdminCoin[];
  meta: CoinListMeta;
};

export type CoinListParams = {
  page?: number;
  per_page?: number;
  field?: string;
  name?: string;
  status?: number;
};

export type CoinDetailResponse = {
  status: boolean;
  data: AdminCoin;
};

export type CoinUpsertPayload = {
  name?: string;
  title?: string;
  type?: number;
  czline?: string;
  czaddress?: string;
  czstatus?: number;
  czminnum?: string | number;
  txstatus?: number;
  txminnum?: string | number;
  txmaxnum?: string | number;
  sxftype?: number;
  txsxf?: string | number;
  txsxf_n?: string | number;
  bbsxf?: string | number;
  hysxf?: string | number;
  bank?: string | number;
  sort?: number;
  status?: number;
};

export type CoinMutationResponse = {
  status: boolean;
  message: string;
  data?: AdminCoin;
};

export type CoinStatusType = "forbid" | "resume" | "delt";

export type CoinStatusResponse = {
  status: boolean;
  message: string;
};
