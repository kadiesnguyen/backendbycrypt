"use client";

import { usePathname, useRouter } from "next/navigation";
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { playContractOrderAlertSound } from "@/features/trading/orders/contract-order-alert/playAlertSound";
import { usePendingCounts } from "@/lib/pending-counts/usePendingCounts";

const DEPOSITS_PATH = "/finance/deposits";
const WITHDRAWALS_PATH = "/finance/withdrawals";
const SEEN_STORAGE_KEY = "admin_finance_alert_seen";

export type FinancePendingAlertSnapshot = {
  deposits: number;
  withdrawals: number;
  total: number;
};

type SeenSnapshot = {
  deposits: number;
  withdrawals: number;
};

function readSeen(): SeenSnapshot | null {
  if (typeof window === "undefined") {
    return null;
  }

  try {
    const raw = window.localStorage.getItem(SEEN_STORAGE_KEY);
    if (!raw) {
      return null;
    }
    const parsed = JSON.parse(raw) as Partial<SeenSnapshot>;
    if (
      typeof parsed.deposits !== "number" ||
      typeof parsed.withdrawals !== "number" ||
      !Number.isFinite(parsed.deposits) ||
      !Number.isFinite(parsed.withdrawals)
    ) {
      return null;
    }
    return {
      deposits: Math.max(0, Math.floor(parsed.deposits)),
      withdrawals: Math.max(0, Math.floor(parsed.withdrawals)),
    };
  } catch {
    return null;
  }
}

function writeSeen(seen: SeenSnapshot): void {
  if (typeof window === "undefined") {
    return;
  }
  window.localStorage.setItem(SEEN_STORAGE_KEY, JSON.stringify(seen));
}

function clampSeen(seen: SeenSnapshot, live: SeenSnapshot): SeenSnapshot {
  return {
    deposits: Math.min(seen.deposits, live.deposits),
    withdrawals: Math.min(seen.withdrawals, live.withdrawals),
  };
}

export function useFinancePendingAlert() {
  const router = useRouter();
  const pathname = usePathname();
  const { data } = usePendingCounts();
  const [isOpen, setIsOpen] = useState(false);
  const [panelOpen, setPanelOpen] = useState(false);
  const [alertData, setAlertData] = useState<FinancePendingAlertSnapshot | null>(null);
  const [seen, setSeen] = useState<SeenSnapshot | null>(null);
  const [ready, setReady] = useState(false);
  const prevUnreadRef = useRef<number | null>(null);
  const hasPlayedSoundRef = useRef(false);

  const deposits = data?.data.deposits ?? 0;
  const withdrawals = data?.data.withdrawals ?? 0;
  const total = deposits + withdrawals;

  useEffect(() => {
    if (!data?.data || ready) {
      return;
    }

    const stored = readSeen();
    if (stored) {
      const live = { deposits, withdrawals };
      const nextSeen = clampSeen(stored, live);
      setSeen(nextSeen);
      if (nextSeen.deposits !== stored.deposits || nextSeen.withdrawals !== stored.withdrawals) {
        writeSeen(nextSeen);
      }
    } else {
      // First visit: baseline silently so existing pending aren't treated as unread.
      const baseline = { deposits, withdrawals };
      setSeen(baseline);
      writeSeen(baseline);
    }

    prevUnreadRef.current = null;
    setReady(true);
  }, [data, deposits, withdrawals, ready]);

  useEffect(() => {
    if (!ready || !data?.data || seen === null) {
      return;
    }

    const live = { deposits, withdrawals };
    const nextSeen = clampSeen(seen, live);
    if (nextSeen.deposits !== seen.deposits || nextSeen.withdrawals !== seen.withdrawals) {
      setSeen(nextSeen);
      writeSeen(nextSeen);
    }
  }, [data, deposits, withdrawals, ready, seen]);

  const unreadDeposits = !ready || seen === null ? 0 : Math.max(0, deposits - seen.deposits);
  const unreadWithdrawals =
    !ready || seen === null ? 0 : Math.max(0, withdrawals - seen.withdrawals);
  const unread = unreadDeposits + unreadWithdrawals;

  useEffect(() => {
    if (!ready || seen === null || !data?.data) {
      return;
    }

    const prevUnread = prevUnreadRef.current;
    prevUnreadRef.current = unread;

    if (prevUnread === null) {
      return;
    }

    if (unread <= prevUnread) {
      return;
    }

    setAlertData({
      deposits: unreadDeposits,
      withdrawals: unreadWithdrawals,
      total: unread,
    });
    setIsOpen(true);
  }, [data, ready, seen, unread, unreadDeposits, unreadWithdrawals]);

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

  const markAsRead = useCallback(() => {
    const nextSeen = { deposits, withdrawals };
    setSeen(nextSeen);
    writeSeen(nextSeen);
    prevUnreadRef.current = 0;
    setIsOpen(false);
    setAlertData(null);
  }, [deposits, withdrawals]);

  const dismiss = useCallback(() => {
    markAsRead();
  }, [markAsRead]);

  const viewDeposits = useCallback(() => {
    markAsRead();
    setPanelOpen(false);
    if (pathname !== DEPOSITS_PATH && !pathname.startsWith(`${DEPOSITS_PATH}/`)) {
      router.push(DEPOSITS_PATH);
    }
  }, [markAsRead, pathname, router]);

  const viewWithdrawals = useCallback(() => {
    markAsRead();
    setPanelOpen(false);
    if (pathname !== WITHDRAWALS_PATH && !pathname.startsWith(`${WITHDRAWALS_PATH}/`)) {
      router.push(WITHDRAWALS_PATH);
    }
  }, [markAsRead, pathname, router]);

  const markAllReadAndClosePanel = useCallback(() => {
    markAsRead();
    setPanelOpen(false);
  }, [markAsRead]);

  return useMemo(
    () => ({
      isOpen,
      alertData,
      dismiss,
      markAsRead: markAllReadAndClosePanel,
      viewDeposits,
      viewWithdrawals,
      panelOpen,
      setPanelOpen,
      deposits,
      withdrawals,
      total,
      unread,
      unreadDeposits,
      unreadWithdrawals,
    }),
    [
      isOpen,
      alertData,
      dismiss,
      markAllReadAndClosePanel,
      viewDeposits,
      viewWithdrawals,
      panelOpen,
      deposits,
      withdrawals,
      total,
      unread,
      unreadDeposits,
      unreadWithdrawals,
    ],
  );
}
