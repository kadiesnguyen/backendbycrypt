"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { usePathname, useRouter } from "next/navigation";
import { useCallback, useEffect, useRef, useState } from "react";
import { fetchContractOrderAlert, markContractOrdersNotified } from "./api";
import { playContractOrderAlertSound } from "./playAlertSound";
import type { ContractOrderAlertData, ContractOrderAlertResponse } from "./types";

export const contractOrderAlertQueryKey = ["admin", "contract-order-alert"] as const;
const CONTRACT_ORDERS_PATH = "/trading/orders";

export function useContractOrderAlert() {
  const queryClient = useQueryClient();
  const router = useRouter();
  const pathname = usePathname();
  const [isOpen, setIsOpen] = useState(false);
  const [alertData, setAlertData] = useState<ContractOrderAlertData | null>(null);
  const hasPlayedSoundRef = useRef(false);
  const hadNewOrdersRef = useRef(false);

  const { data } = useQuery({
    queryKey: contractOrderAlertQueryKey,
    queryFn: fetchContractOrderAlert,
    refetchInterval: 5_000,
    refetchIntervalInBackground: true,
    staleTime: 0,
  });

  useEffect(() => {
    const payload = data?.data;

    if (!payload?.has_new) {
      hadNewOrdersRef.current = false;
      return;
    }

    setAlertData(payload);

    if (!hadNewOrdersRef.current) {
      hadNewOrdersRef.current = true;
      setIsOpen(true);
    }
  }, [data]);

  useEffect(() => {
    if (!isOpen) {
      hasPlayedSoundRef.current = false;
      return;
    }

    if (hasPlayedSoundRef.current) {
      return;
    }

    playContractOrderAlertSound();
    hasPlayedSoundRef.current = true;
  }, [isOpen]);

  const acknowledgeAlert = useCallback(() => {
    setIsOpen(false);
    setAlertData(null);

    queryClient.setQueryData<ContractOrderAlertResponse>(contractOrderAlertQueryKey, (current) => {
      if (!current?.data) return current;

      return {
        ...current,
        data: {
          ...current.data,
          count: 0,
          has_new: false,
          orders: [],
        },
      };
    });
  }, [queryClient]);

  const dismissMutation = useMutation({
    mutationFn: markContractOrdersNotified,
    onSuccess: async () => {
      await Promise.all([
        queryClient.invalidateQueries({ queryKey: contractOrderAlertQueryKey }),
        queryClient.refetchQueries({ queryKey: ["admin", "contract-orders"], type: "active" }),
        queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] }),
      ]);
    },
  });

  const dismiss = useCallback(() => {
    if (dismissMutation.isPending) {
      return;
    }

    acknowledgeAlert();
    dismissMutation.mutate();
  }, [acknowledgeAlert, dismissMutation]);

  const viewOrders = useCallback(() => {
    if (dismissMutation.isPending) {
      return;
    }

    acknowledgeAlert();
    dismissMutation.mutate(undefined, {
      onSuccess: () => {
        if (pathname !== CONTRACT_ORDERS_PATH && !pathname.startsWith(`${CONTRACT_ORDERS_PATH}/`)) {
          router.push(CONTRACT_ORDERS_PATH);
        }
      },
    });
  }, [acknowledgeAlert, dismissMutation, pathname, router]);

  return {
    isOpen,
    alertData,
    dismiss,
    viewOrders,
    isDismissing: dismissMutation.isPending,
  };
}
