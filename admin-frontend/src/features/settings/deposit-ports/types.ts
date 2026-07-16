export type AdminDepositPort = {
  id: number;
  name: string;
  wallet: string | null;
  address: string | null;
  coin: string | null;
  status: number;
};

export type DepositPortCoinOption = {
  id: number;
  name: string;
  title: string | null;
};

export type DepositPortFormMeta = {
  coins: DepositPortCoinOption[];
};

export type DepositPortListResponse = {
  status: boolean;
  data: AdminDepositPort[];
};

export type DepositPortDetailResponse = {
  status: boolean;
  data: AdminDepositPort;
  meta?: DepositPortFormMeta;
};

export type DepositPortFormMetaResponse = {
  status: boolean;
  data: DepositPortFormMeta;
};

export type DepositPortUpsertPayload = {
  name: string;
  wallet?: string;
  address?: string;
  coin?: string;
  status?: number;
};

export type DepositPortMutationResponse = {
  status: boolean;
  message: string;
  data?: AdminDepositPort;
};

export type DepositPortStatusType = "forbid" | "resume" | "delete";

export type DepositPortStatusResponse = {
  status: boolean;
  message: string;
};
