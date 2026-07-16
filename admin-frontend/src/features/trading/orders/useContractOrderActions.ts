"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { contractOrderAlertQueryKey } from "./contract-order-alert/useContractOrderAlert";
import { setContractWinLoss, settleContractOrder, settleStuckContractOrders } from "./api";
import { contractOrdersQueryKey } from "./query-keys";
import type { ContractOrdersListResponse } from "./types";

function removeOrderFromCache(
  queryClient: ReturnType<typeof useQueryClient>,
  orderId: number,
) {
  queryClient.setQueriesData<ContractOrdersListResponse>(
    { queryKey: contractOrdersQueryKey },
    (old) => {
      if (!old?.data) return old;

      const nextData = old.data.filter((order) => order.id !== orderId);
      if (nextData.length === old.data.length) return old;

      return {
        ...old,
        data: nextData,
        meta: old.meta
          ? {
              ...old.meta,
              total: Math.max(0, old.meta.total - 1),
            }
          : old.meta,
      };
    },
  );
}

export function useContractOrderActions() {
  const queryClient = useQueryClient();

  const syncLists = async () => {
    await Promise.all([
      queryClient.refetchQueries({ queryKey: contractOrdersQueryKey, type: "active" }),
      queryClient.invalidateQueries({ queryKey: contractOrderAlertQueryKey }),
      queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] }),
    ]);
  };

  const setWinLoss = useMutation({
    mutationFn: setContractWinLoss,
    onSuccess: syncLists,
  });

  const manualSettle = useMutation({
    mutationFn: settleContractOrder,
    onSuccess: async (_response, orderId) => {
      removeOrderFromCache(queryClient, orderId);
      await syncLists();
    },
  });

  const settleStuck = useMutation({
    mutationFn: settleStuckContractOrders,
    onSuccess: syncLists,
  });

  return { setWinLoss, manualSettle, settleStuck };
}
