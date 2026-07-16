"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchMiner, fetchMinerFormMeta, fetchMiners } from "./api";
import type { MinersListParams } from "./types";

export function useMiners(params: MinersListParams) {
  return useQuery({
    queryKey: ["admin", "miners", params],
    queryFn: () => fetchMiners(params),
    placeholderData: (prev) => prev,
  });
}

export function useMiner(id: number | null) {
  return useQuery({
    queryKey: ["admin", "miners", "detail", id],
    queryFn: () => fetchMiner(id!),
    enabled: id !== null && id > 0,
  });
}

export function useMinerFormMeta(enabled: boolean) {
  return useQuery({
    queryKey: ["admin", "miners", "form-meta"],
    queryFn: fetchMinerFormMeta,
    enabled,
    staleTime: 60_000,
  });
}
