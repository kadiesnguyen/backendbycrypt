"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchBills } from "./api";
import type { BillListParams } from "./types";

export const billsQueryKey = ["admin", "bills"] as const;

export function useBills(params: BillListParams) {
  return useQuery({
    queryKey: [...billsQueryKey, params],
    queryFn: () => fetchBills(params),
  });
}
