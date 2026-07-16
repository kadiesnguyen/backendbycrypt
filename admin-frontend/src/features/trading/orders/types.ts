export type ContractOrder = {
  id: number;
  uid: number;
  username: string;
  coinname: string;
  num: string;
  hybl: string;
  hyzd: number;
  hyzd_label: string;
  status: number;
  status_label: string;
  is_win: number;
  is_win_label: string;
  buytime: string | number;
  selltime: string | number | null;
  intselltime: number;
  buyprice: string;
  sellprice: string | null;
  ploss: string | null;
  time: number;
  kongyk: number;
  kongyk_label: string;
  invit: string | null;
  tznum: number;
};

export type ContractOrdersListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type ContractOrdersListResponse = {
  status: boolean;
  data: ContractOrder[];
  meta: ContractOrdersListMeta;
};

export type ContractOrdersListParams = {
  page?: number;
  per_page?: number;
  username?: string;
  hyzd?: number;
};

export type SetWinLossPayload = {
  id: number;
  kongyk: 0 | 1 | 2;
};

export type ActionResponse = {
  status: boolean;
  message: string;
};
