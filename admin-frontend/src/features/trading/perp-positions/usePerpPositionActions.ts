"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { perpPositionAlertQueryKey } from "./perp-position-alert/usePerpPositionAlert";
import { settlePerpPosition, setPerpWinLoss } from "./api";
import { perpPositionsQueryKey } from "./query-keys";
import type { PerpPositionsListResponse } from "./types";

function removeFromOpenCache(queryClient: ReturnType<typeof useQueryClient>, id: number) {
  queryClient.setQueriesData<PerpPositionsListResponse>(
    { queryKey: perpPositionsQueryKey },
    (old) => {
      if (!old?.data) return old;
      const next = old.data.filter((p) => p.id !== id);
      if (next.length === old.data.length) return old;
      return {
        ...old,
        data: next,
        meta: old.meta ? { ...old.meta, total: Math.max(0, old.meta.total - 1) } : old.meta,
      };
    },
  );
}

export function usePerpPositionActions() {
  const queryClient = useQueryClient();

  const sync = async () => {
    await Promise.all([
      queryClient.refetchQueries({ queryKey: perpPositionsQueryKey, type: "active" }),
      queryClient.invalidateQueries({ queryKey: perpPositionAlertQueryKey }),
      queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] }),
    ]);
  };

  const setWinLoss = useMutation({
    mutationFn: setPerpWinLoss,
    onSuccess: sync,
  });

  const settle = useMutation({
    mutationFn: settlePerpPosition,
    onSuccess: async (_res, id) => {
      removeFromOpenCache(queryClient, id);
      await sync();
    },
  });

  return { setWinLoss, settle };
}
