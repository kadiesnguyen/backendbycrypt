"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { fetchAsset, updateAsset } from "./api";

export function useAsset(id: number | null) {
  return useQuery({
    queryKey: ["admin", "user-assets", "detail", id],
    queryFn: () => fetchAsset(id!),
    enabled: id !== null,
  });
}

export function useAssetActions() {
  const queryClient = useQueryClient();

  const update = useMutation({
    mutationFn: ({ id, balances }: { id: number; balances: Record<string, string | number> }) =>
      updateAsset(id, balances),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin", "user-assets"] });
    },
  });

  return { update };
}
