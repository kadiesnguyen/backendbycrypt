"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createDepositPort, updateDepositPort, updateDepositPortStatus } from "./api";
import type { DepositPortStatusType, DepositPortUpsertPayload } from "./types";

export function useDepositPortActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "deposit-ports"] });
  };

  const create = useMutation({
    mutationFn: (payload: DepositPortUpsertPayload) => createDepositPort(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: DepositPortUpsertPayload }) =>
      updateDepositPort(id, payload),
    onSuccess: invalidate,
  });

  const updateStatus = useMutation({
    mutationFn: ({ ids, type }: { ids: number[]; type: DepositPortStatusType }) =>
      updateDepositPortStatus(ids, type),
    onSuccess: invalidate,
  });

  return { create, update, updateStatus };
}
