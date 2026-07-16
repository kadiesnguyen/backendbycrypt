export type AdminMiner = {
  id: number;
  type: number;
  rtype: number;
  sharebl: string | null;
  sharecode: string | null;
  title: string;
  content: string | null;
  imgs: string | null;
  outtype: number;
  dayoutnum: string;
  outcoin: string;
  pricenum: string;
  pricecoin: string;
  cycle: number;
  suanl: string;
  allnum: number;
  ycnum: number;
  sellnum: number;
  jlnum: string;
  jlcoin: string;
  buyask: number;
  asknum: number;
  djout: number;
  djday: number;
  status: number;
  status_label: string;
  buymax: number;
  addtime: string | null;
};

export type MinersListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type MinersListResponse = {
  status: boolean;
  data: AdminMiner[];
  meta: MinersListMeta;
};

export type MinersListParams = {
  page?: number;
  per_page?: number;
};

export type MinerCoinOption = {
  id: number;
  name: string;
  title: string | null;
};

export type MinerFormMeta = {
  coins: MinerCoinOption[];
};

export type MinerUpsertPayload = {
  title: string;
  rtype: number;
  type: number;
  sharebl?: string;
  content?: string;
  imgs?: string;
  dayoutnum: string;
  outtype: number;
  outcoin: string;
  pricenum: string;
  pricecoin: string;
  buymax: number;
  cycle: number;
  suanl: string;
  allnum: number;
  ycnum: number;
  jlnum: string;
  jlcoin: string;
  buyask: number;
  asknum: number;
  djout: number;
  djday?: number;
};

export type MinerFormMetaResponse = {
  status: boolean;
  data: MinerFormMeta;
};

export type MinerDetailResponse = {
  status: boolean;
  data: AdminMiner;
  meta?: MinerFormMeta;
};

export type MinerMutationResponse = {
  status: boolean;
  message: string;
  data?: AdminMiner;
};

export type MinerStatusType = "1" | "2" | "3";

export type MinerStatusResponse = {
  status: boolean;
  message: string;
};
