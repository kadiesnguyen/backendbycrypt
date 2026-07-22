"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { usePathname, useRouter } from "next/navigation";
import { useCallback, useEffect, useRef, useState } from "react";
import { playContractOrderAlertSound } from "@/features/trading/orders/contract-order-alert/playAlertSound";
import { fetchPerpPositionAlert, markPerpPositionsNotified } from "./api";
import type { PerpPositionAlertData, PerpPositionAlertResponse } from "./types";

export const perpPositionAlertQueryKey = ["admin", "perp-position-alert"] as const;
const PERP_PATH = "/trading/perp";

export function usePerpPositionAlert() {
  const queryClient = useQueryClient();
  const router = useRouter();
  const pathname = usePathname();
  const [isOpen, setIsOpen] = useState(false);
  const [alertData, setAlertData] = useState<PerpPositionAlertData | null>(null);
  const hasPlayedSoundRef = useRef(false);
  const hadNewRef = useRef(false);

  const { data } = useQuery({
    queryKey: perpPositionAlertQueryKey,
    queryFn: fetchPerpPositionAlert,
    refetchInterval: 5_000,
    refetchIntervalInBackground: true,
    staleTime: 0,
  });

  useEffect(() => {
    const payload = data?.data;
    if (!payload?.has_new) {
      hadNewRef.current = false;
      return;
    }
    setAlertData(payload);
    if (!hadNewRef.current) {
      hadNewRef.current = true;
      setIsOpen(true);
    }
  }, [data]);

  useEffect(() => {
    if (!isOpen) {
      hasPlayedSoundRef.current = false;
      return;
    }
    if (hasPlayedSoundRef.current) return;
    playContractOrderAlertSound();
    hasPlayedSoundRef.current = true;
  }, [isOpen]);

  const acknowledgeAlert = useCallback(() => {
    setIsOpen(false);
    setAlertData(null);
    queryClient.setQueryData<PerpPositionAlertResponse>(perpPositionAlertQueryKey, (current) => {
      if (!current?.data) return current;
      return {
        ...current,
        data: { ...current.data, count: 0, has_new: false, positions: [] },
      };
    });
  }, [queryClient]);

  const dismissMutation = useMutation({
    mutationFn: markPerpPositionsNotified,
    onSuccess: async () => {
      await Promise.all([
        queryClient.invalidateQueries({ queryKey: perpPositionAlertQueryKey }),
        queryClient.refetchQueries({ queryKey: ["admin", "perp-positions"], type: "active" }),
        queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] }),
      ]);
    },
  });

  const dismiss = useCallback(() => {
    if (dismissMutation.isPending) return;
    acknowledgeAlert();
    dismissMutation.mutate();
  }, [acknowledgeAlert, dismissMutation]);

  const viewPositions = useCallback(() => {
    if (dismissMutation.isPending) return;
    acknowledgeAlert();
    dismissMutation.mutate(undefined, {
      onSuccess: () => {
        if (pathname !== PERP_PATH && !pathname.startsWith(`${PERP_PATH}/`)) {
          router.push(PERP_PATH);
        }
      },
    });
  }, [acknowledgeAlert, dismissMutation, pathname, router]);

  return {
    isOpen,
    alertData,
    dismiss,
    viewPositions,
    isDismissing: dismissMutation.isPending,
  };
}
