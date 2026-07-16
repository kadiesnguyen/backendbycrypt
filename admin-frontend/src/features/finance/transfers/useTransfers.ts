"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchTransfers } from "./api";
import type { TransferListParams } from "./types";
export const transfersQueryKey = ["admin", "transfers"] as const;
export function useTransfers(params: TransferListParams) {
  return useQuery({ queryKey: [...transfersQueryKey, params], queryFn: () => fetchTransfers(params) });
}
