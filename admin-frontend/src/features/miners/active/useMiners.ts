"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchMinerRows } from "./api";
import type { MinerListParams } from "./types";
export const mineractiveKey = ["admin", "miners-active"] as const;
export function useMinerRows(params: MinerListParams) { return useQuery({ queryKey: [...mineractiveKey, params], queryFn: () => fetchMinerRows(params) }); }
