"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { fetchSpotSettings, updateSpotSettings } from "./api";
export const spotSettingsKey = ["admin", "spot-settings"] as const;
export function useSpotSettings() { return useQuery({ queryKey: spotSettingsKey, queryFn: fetchSpotSettings }); }
export function useSpotSettingsActions() {
  const qc = useQueryClient();
  return useMutation({ mutationFn: updateSpotSettings, onSuccess: () => qc.invalidateQueries({ queryKey: spotSettingsKey }) });
}
