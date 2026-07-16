export type AdminStake = {
  id: number;
  name: string;
  min: string;
  max: string;
  open: number;
  percent: string;
  imgs: string | null;
  content: string | null;
  addtime: string | number | null;
  status: number;
  status_label: string;
  state: number;
  state_label: string;
};

export type AdminStakingLog = {
  id: number;
  pid: number;
  uid: number;
  account: string;
  name: string;
  num: string;
  open: number;
  percent: string;
  addtime: string | number | null;
  endtime: string | number | null;
  endday: string | number | null;
  status: number;
  status_label: string;
};

export type ListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type StakesListResponse = {
  status: boolean;
  data: AdminStake[];
  meta: ListMeta;
};

export type StakingLogsListResponse = {
  status: boolean;
  data: AdminStakingLog[];
  meta: ListMeta;
};

export type StakesListParams = {
  page?: number;
  per_page?: number;
};

export type StakingLogsListParams = {
  page?: number;
  per_page?: number;
  account?: string;
};

export type StakeCoinOption = {
  name: string;
  title: string | null;
};

export type StakeFormMeta = {
  clist: StakeCoinOption[];
  paylist: StakeCoinOption[];
  alllist: StakeCoinOption[];
};

export type StakeFormMetaResponse = {
  status: boolean;
  data: StakeFormMeta;
};

export type StakeDetailResponse = {
  status: boolean;
  data: AdminStake;
  meta: StakeFormMeta;
};

export type StakeUpsertPayload = {
  name: string;
  min: number | string;
  max: number | string;
  open: number;
  percent: number | string;
  imgs?: string;
  content?: string;
  status: number;
  state: number;
};

export type StakeMutationResponse = {
  status: boolean;
  message: string;
  data: AdminStake;
};
