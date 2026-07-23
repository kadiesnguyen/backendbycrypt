"use client";

import { FinancePendingAlertDialog } from "./FinancePendingAlertDialog";
import { useFinancePendingAlertContext } from "./FinancePendingAlertContext";

export function FinancePendingAlert() {
  const { isOpen, alertData, dismiss, viewDeposits, viewWithdrawals } =
    useFinancePendingAlertContext();

  return (
    <FinancePendingAlertDialog
      isOpen={isOpen}
      alertData={alertData}
      onDismiss={dismiss}
      onViewDeposits={viewDeposits}
      onViewWithdrawals={viewWithdrawals}
    />
  );
}
