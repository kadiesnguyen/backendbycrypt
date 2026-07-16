"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchStake, fetchStakeFormMeta, fetchStakes, fetchStakingLogs } from "./api";
import type { StakesListParams, StakingLogsListParams } from "./types";

export function useStakes(params: StakesListParams) {
  return useQuery({
    queryKey: ["admin", "stakes", params],
    queryFn: () => fetchStakes(params),
    placeholderData: (prev) => prev,
  });
}

export function useStakingLogs(params: StakingLogsListParams) {
  return useQuery({
    queryKey: ["admin", "staking-logs", params],
    queryFn: () => fetchStakingLogs(params),
    placeholderData: (prev) => prev,
  });
}

export function useStake(id: number | null) {
  return useQuery({
    queryKey: ["admin", "stakes", "detail", id],
    queryFn: () => fetchStake(id!),
    enabled: id !== null && id > 0,
  });
}

export function useStakeFormMeta(enabled: boolean) {
  return useQuery({
    queryKey: ["admin", "stakes", "form-meta"],
    queryFn: fetchStakeFormMeta,
    enabled,
    staleTime: 60_000,
  });
}
