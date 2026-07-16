"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import {
  createContractMarket,
  fetchContractMarket,
  fetchMarketFormMeta,
  updateContractMarket,
} from "./api";
import type { UpsertMarketPayload } from "./types";

export function useMarketFormMeta() {
  return useQuery({
    queryKey: ["admin", "contract-markets", "form-meta"],
    queryFn: fetchMarketFormMeta,
  });
}

export function useContractMarketDetail(id: number | null) {
  return useQuery({
    queryKey: ["admin", "contract-markets", id],
    queryFn: () => fetchContractMarket(id!),
    enabled: id !== null,
  });
}

export function useContractMarketActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "contract-markets"] });
  };

  const create = useMutation({
    mutationFn: (payload: UpsertMarketPayload) => createContractMarket(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: UpsertMarketPayload }) =>
      updateContractMarket(id, payload),
    onSuccess: invalidate,
  });

  return { create, update };
}
