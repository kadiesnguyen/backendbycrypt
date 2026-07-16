"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchContractOrders } from "./api";
import { contractOrdersListKey } from "./query-keys";
import type { ContractOrdersListParams } from "./types";

const ORDERS_POLL_MS = 5_000;

export function useContractOrders(params: ContractOrdersListParams) {
  return useQuery({
    queryKey: contractOrdersListKey(params),
    queryFn: () => fetchContractOrders(params),
    placeholderData: (prev) => prev,
    staleTime: 0,
    refetchInterval: ORDERS_POLL_MS,
    refetchIntervalInBackground: true,
  });
}
