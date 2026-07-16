"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchOrders } from "./api";
import type { OrderListParams } from "./types";
export const trialordersQueryKey = ["admin", "trial-orders"] as const;
export function useTrialOrders(params: OrderListParams) { return useQuery({ queryKey: [...trialordersQueryKey, params], queryFn: () => fetchOrders(params) }); }
