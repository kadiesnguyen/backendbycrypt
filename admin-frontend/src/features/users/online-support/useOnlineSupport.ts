"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchSupportUsers } from "./api";
import type { SupportListParams } from "./types";
export const onlineSupportQueryKey = ["admin", "online-support"] as const;
export function useOnlineSupport(params: SupportListParams) { return useQuery({ queryKey: [...onlineSupportQueryKey, params], queryFn: () => fetchSupportUsers(params) }); }
