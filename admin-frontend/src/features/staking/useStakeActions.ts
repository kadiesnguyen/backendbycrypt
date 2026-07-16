"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createStake, updateStake } from "./api";
import type { StakeUpsertPayload } from "./types";

export function useStakeActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "stakes"] });
  };

  const create = useMutation({
    mutationFn: (payload: StakeUpsertPayload) => createStake(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: StakeUpsertPayload }) =>
      updateStake(id, payload),
    onSuccess: invalidate,
  });

  return { create, update };
}
