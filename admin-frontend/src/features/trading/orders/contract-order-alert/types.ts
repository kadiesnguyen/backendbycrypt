import type { ContractOrder } from "../types";

export type ContractOrderAlertData = {
  count: number;
  has_new: boolean;
  orders: ContractOrder[];
};

export type ContractOrderAlertResponse = {
  status: boolean;
  code?: number;
  data: ContractOrderAlertData;
};

export type MarkContractOrdersNotifiedResponse = {
  status: boolean;
  message: string;
  code?: number;
  data?: {
    updated: number;
  };
};
