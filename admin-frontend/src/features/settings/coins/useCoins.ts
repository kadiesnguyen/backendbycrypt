"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchCoin, fetchCoins } from "./api";
import type { CoinListParams } from "./types";

export function useCoins(params: CoinListParams) {
  return useQuery({
    queryKey: ["admin", "coins", params],
    queryFn: () => fetchCoins(params),
    placeholderData: (prev) => prev,
  });
}

export function useCoin(id: number | null) {
  return useQuery({
    queryKey: ["admin", "coins", "detail", id],
    queryFn: () => fetchCoin(id!),
    enabled: id !== null && id > 0,
  });
}
