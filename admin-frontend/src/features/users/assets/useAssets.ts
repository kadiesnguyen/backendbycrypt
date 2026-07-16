"use client";
import { useQuery } from "@tanstack/react-query";
import { fetchAssets } from "./api";
import type { AssetListParams } from "./types";
export const assetsQueryKey = ["admin", "assets"] as const;
export function useAssets(params: AssetListParams) {
  return useQuery({ queryKey: [...assetsQueryKey, params], queryFn: () => fetchAssets(params) });
}
