"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import {
  contractQueueAction,
  deleteContractQueueEntry,
  updateContractQueueEntry,
} from "./api";
import type { QueueAction, UpdateQueueEntryPayload } from "./types";

export function useContractQueueActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "contract-queue"] });
  };

  const action = useMutation({
    mutationFn: (queueAction: QueueAction) => contractQueueAction(queueAction),
    onSuccess: invalidate,
  });

  const updateEntry = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: UpdateQueueEntryPayload }) =>
      updateContractQueueEntry(id, payload),
    onSuccess: invalidate,
  });

  const deleteEntry = useMutation({
    mutationFn: (id: number) => deleteContractQueueEntry(id),
    onSuccess: invalidate,
  });

  return { action, updateEntry, deleteEntry };
}
