export type AdminWithdrawal = {
  id: number;
  userid: number;
  username: string;
  coinname: string;
  wallet: string | null;
  method_label: string;
  address: string | null;
  num: string;
  fee: string;
  mum: string;
  txid: string | null;
  addtime: string | number;
  endtime: string | null;
  status: number;
  status_label: string;
};

export type WithdrawalsListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type WithdrawalsListResponse = {
  status: boolean;
  data: AdminWithdrawal[];
  meta: WithdrawalsListMeta;
};

export type WithdrawalsListParams = {
  page?: number;
  per_page?: number;
  username?: string;
};

export type WithdrawalActionResponse = {
  status: boolean;
  message: string;
};
