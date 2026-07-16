"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchWallets } from "./api";
import type { WalletListParams } from "./types";

export const walletsQueryKey = ["admin", "wallets"] as const;

export function useWallets(params: WalletListParams) {
  return useQuery({
    queryKey: [...walletsQueryKey, params],
    queryFn: () => fetchWallets(params),
  });
}
