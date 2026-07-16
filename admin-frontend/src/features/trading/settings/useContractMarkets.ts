"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchContractMarkets } from "./api";
import type { MarketsListParams } from "./types";

export function useContractMarkets(params: MarketsListParams) {
  return useQuery({
    queryKey: ["admin", "contract-markets", params],
    queryFn: () => fetchContractMarkets(params),
    placeholderData: (prev) => prev,
  });
}
