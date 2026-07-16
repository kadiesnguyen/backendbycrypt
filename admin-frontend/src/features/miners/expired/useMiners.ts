"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchMinerRows } from "./api";
import type { MinerListParams } from "./types";
export const minerexpiredKey = ["admin", "miners-expired"] as const;
export function useMinerRows(params: MinerListParams) { return useQuery({ queryKey: [...minerexpiredKey, params], queryFn: () => fetchMinerRows(params) }); }
