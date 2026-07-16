"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createMiner, updateMiner, updateMinerStatus } from "./api";
import type { MinerStatusType, MinerUpsertPayload } from "./types";

export function useMinerActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "miners"] });
  };

  const create = useMutation({
    mutationFn: (payload: MinerUpsertPayload) => createMiner(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: MinerUpsertPayload }) =>
      updateMiner(id, payload),
    onSuccess: invalidate,
  });

  const updateStatus = useMutation({
    mutationFn: ({ ids, type }: { ids: number[]; type: MinerStatusType }) =>
      updateMinerStatus(ids, type),
    onSuccess: invalidate,
  });

  return { create, update, updateStatus };
}
