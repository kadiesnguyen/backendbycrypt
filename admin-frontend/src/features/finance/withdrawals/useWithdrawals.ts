"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchWithdrawals } from "./api";
import type { WithdrawalsListParams } from "./types";

export function useWithdrawals(params: WithdrawalsListParams) {
  return useQuery({
    queryKey: ["admin", "withdrawals", params],
    queryFn: () => fetchWithdrawals(params),
    placeholderData: (prev) => prev,
  });
}
