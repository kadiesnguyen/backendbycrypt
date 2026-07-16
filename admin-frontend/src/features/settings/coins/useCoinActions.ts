"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createCoin, updateCoin, updateCoinStatus } from "./api";
import type { CoinStatusType, CoinUpsertPayload } from "./types";

export function useCoinActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "coins"] });
  };

  const create = useMutation({
    mutationFn: (payload: CoinUpsertPayload) => createCoin(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: CoinUpsertPayload }) =>
      updateCoin(id, payload),
    onSuccess: invalidate,
  });

  const updateStatus = useMutation({
    mutationFn: ({ ids, type }: { ids: number[]; type: CoinStatusType }) =>
      updateCoinStatus(ids, type),
    onSuccess: invalidate,
  });

  return { create, update, updateStatus };
}
