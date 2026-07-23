"use client";

import { createContext, useContext, type ReactNode } from "react";
import {
  useFinancePendingAlert,
  type FinancePendingAlertSnapshot,
} from "./useFinancePendingAlert";

type FinancePendingAlertContextValue = ReturnType<typeof useFinancePendingAlert>;

const FinancePendingAlertContext = createContext<FinancePendingAlertContextValue | null>(null);

export function FinancePendingAlertProvider({ children }: { children: ReactNode }) {
  const value = useFinancePendingAlert();
  return (
    <FinancePendingAlertContext.Provider value={value}>
      {children}
    </FinancePendingAlertContext.Provider>
  );
}

export function useFinancePendingAlertContext(): FinancePendingAlertContextValue {
  const ctx = useContext(FinancePendingAlertContext);
  if (!ctx) {
    throw new Error("useFinancePendingAlertContext must be used within FinancePendingAlertProvider");
  }
  return ctx;
}

export type { FinancePendingAlertSnapshot };
