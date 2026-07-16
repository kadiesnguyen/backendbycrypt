"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchMinerRows } from "./api";
import type { MinerListParams } from "./types";
export const minerprofitsKey = ["admin", "miners-profits"] as const;
export function useMinerRows(params: MinerListParams) { return useQuery({ queryKey: [...minerprofitsKey, params], queryFn: () => fetchMinerRows(params) }); }
