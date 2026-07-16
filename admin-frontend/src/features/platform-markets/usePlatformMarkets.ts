"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createPlatformMarket, fetchPlatformMarket, fetchPlatformMarkets, updatePlatformMarket } from "./api";
import type { PlatformMarketListParams, PlatformMarketPayload } from "./types";
export const platformMarketsKey = ["admin", "platform-markets"] as const;
export function usePlatformMarkets(params: PlatformMarketListParams) { return useQuery({ queryKey: [...platformMarketsKey, params], queryFn: () => fetchPlatformMarkets(params) }); }
export function usePlatformMarket(id: number | null) { return useQuery({ queryKey: [...platformMarketsKey, id], queryFn: () => fetchPlatformMarket(id!), enabled: id !== null }); }
export function usePlatformMarketActions() {
  const qc = useQueryClient();
  return {
    create: useMutation({ mutationFn: createPlatformMarket, onSuccess: () => qc.invalidateQueries({ queryKey: platformMarketsKey }) }),
    update: useMutation({ mutationFn: ({ id, payload }: { id: number; payload: PlatformMarketPayload }) => updatePlatformMarket(id, payload), onSuccess: () => qc.invalidateQueries({ queryKey: platformMarketsKey }) }),
  };
}
