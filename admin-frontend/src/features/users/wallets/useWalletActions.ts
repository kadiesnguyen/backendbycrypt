"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createWallet, deleteWallets, fetchWallet, updateWallet } from "./api";
import type { WalletUpsertPayload } from "./api";

export function useWallet(id: number | null) {
  return useQuery({
    queryKey: ["admin", "user-wallets", "detail", id],
    queryFn: () => fetchWallet(id!),
    enabled: id !== null,
  });
}

export function useWalletActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "user-wallets"] });
  };

  const create = useMutation({
    mutationFn: (payload: WalletUpsertPayload) => createWallet(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: WalletUpsertPayload }) =>
      updateWallet(id, payload),
    onSuccess: invalidate,
  });

  const remove = useMutation({
    mutationFn: (ids: number[]) => deleteWallets(ids),
    onSuccess: invalidate,
  });

  return { create, update, remove };
}
