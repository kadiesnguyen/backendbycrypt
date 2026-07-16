"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchMinerRows } from "./api";
import type { MinerListParams } from "./types";
export const minerfrozenprofitsKey = ["admin", "miners-frozen-profits"] as const;
export function useMinerRows(params: MinerListParams) { return useQuery({ queryKey: [...minerfrozenprofitsKey, params], queryFn: () => fetchMinerRows(params) }); }
