export type AdminDeposit = {
  id: number;
  uid: number;
  username: string;
  coin: string;
  method: number;
  method_label: string;
  address: string | null;
  num: string;
  num_real: string | null;
  payimg: string | null;
  msg: string | null;
  addtime: string | number;
  updatetime: string | null;
  status: number;
  status_label: string;
};

export type DepositsListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type DepositsListResponse = {
  status: boolean;
  data: AdminDeposit[];
  meta: DepositsListMeta;
};

export type DepositsListParams = {
  page?: number;
  per_page?: number;
  username?: string;
};

export type DepositActionResponse = {
  status: boolean;
  message: string;
};
