"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createCtMarket, fetchCtMarkets, updateCtMarket } from "./api";
import type { CtMarketListParams, CtMarketPayload } from "./types";
export const ctMarketsKey = ["admin", "ct-markets"] as const;
export function useCtMarkets(params: CtMarketListParams) { return useQuery({ queryKey: [...ctMarketsKey, params], queryFn: () => fetchCtMarkets(params) }); }
export function useCtMarketActions() { const qc = useQueryClient(); return { create: useMutation({ mutationFn: createCtMarket, onSuccess: () => qc.invalidateQueries({ queryKey: ctMarketsKey }) }), update: useMutation({ mutationFn: ({ id, payload }: { id: number; payload: CtMarketPayload }) => updateCtMarket(id, payload), onSuccess: () => qc.invalidateQueries({ queryKey: ctMarketsKey }) }) }; }
