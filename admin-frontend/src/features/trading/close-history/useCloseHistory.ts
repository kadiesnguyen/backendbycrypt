"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchOrders } from "./api";
import type { OrderListParams } from "./types";
export const closehistoryQueryKey = ["admin", "close-history"] as const;
export function useCloseHistory(params: OrderListParams) { return useQuery({ queryKey: [...closehistoryQueryKey, params], queryFn: () => fetchOrders(params) }); }
