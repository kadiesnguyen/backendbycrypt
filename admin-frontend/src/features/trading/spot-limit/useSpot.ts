"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchSpotOrders } from "./api";
import type { SpotListParams } from "./types";
export const spotlimitKey = ["admin", "spot-limit"] as const;
export function useSpotLimit(params: SpotListParams) { return useQuery({ queryKey: [...spotlimitKey, params], queryFn: () => fetchSpotOrders(params) }); }
