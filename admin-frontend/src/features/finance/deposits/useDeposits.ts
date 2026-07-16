"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchDeposits } from "./api";
import type { DepositsListParams } from "./types";

export function useDeposits(params: DepositsListParams) {
  return useQuery({
    queryKey: ["admin", "deposits", params],
    queryFn: () => fetchDeposits(params),
    placeholderData: (prev) => prev,
  });
}
