import { apiClient } from "@/lib/api-client";
import type {
  ContractOrderAlertResponse,
  MarkContractOrdersNotifiedResponse,
} from "./types";

export function fetchContractOrderAlert(): Promise<ContractOrderAlertResponse> {
  return apiClient<ContractOrderAlertResponse>("/contract-orders/pending-count");
}

export function markContractOrdersNotified(): Promise<MarkContractOrdersNotifiedResponse> {
  return apiClient<MarkContractOrdersNotifiedResponse>("/contract-orders/mark-notified", {
    method: "POST",
  });
}
