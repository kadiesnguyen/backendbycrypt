"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchSpotOrders } from "./api";
import type { SpotListParams } from "./types";
export const spotmarketKey = ["admin", "spot-market"] as const;
export function useSpotMarket(params: SpotListParams) { return useQuery({ queryKey: [...spotmarketKey, params], queryFn: () => fetchSpotOrders(params) }); }
