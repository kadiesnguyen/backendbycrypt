export type AdminUser = {
  id: number;
  username: string;
  fullname: string | null;
  phonenumber: string | null;
  cccd?: string | null;
  status: number;
  txstate: number;
  level: number;
  is_agent: number;
  rzstatus?: number;
  addtime: number;
  lgtime: string | null;
  login_state: string | null;
  invit?: string | null;
  invit_1?: number;
  invit_2?: number;
  invit_3?: number;
  hy_result_mode?: number;
  kefu?: number | string | null;
  bank_name?: string | null;
  bank_acc_no?: string | null;
  bank_acc_name?: string | null;
  wallet?: string | null;
  cardzm?: string | null;
  cardfm?: string | null;
  rztime?: number;
  rzuptime?: number;
  trade_locked?: number;
  trade_lock_msg?: string | null;
  usdt?: string;
  btc?: string;
  eth?: string;
};

export type KycMinerOption = {
  id: number;
  title: string;
};

export type KycFormData = {
  id: number;
  username: string;
  cccd: string | null;
  cardzm: string | null;
  cardfm: string | null;
  rztime: number;
  miners: KycMinerOption[];
};

export type UsersListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type UsersListResponse = {
  status: boolean;
  data: AdminUser[];
  meta: UsersListMeta;
};

export type UsersListParams = {
  page?: number;
  per_page?: number;
  username?: string;
  status?: number;
};
