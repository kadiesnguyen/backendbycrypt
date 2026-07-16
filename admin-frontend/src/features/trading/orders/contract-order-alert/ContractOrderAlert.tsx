"use client";

import { ContractOrderAlertDialog } from "./ContractOrderAlertDialog";
import { useContractOrderAlert } from "./useContractOrderAlert";

export function ContractOrderAlert() {
  const { isOpen, alertData, dismiss, viewOrders, isDismissing } = useContractOrderAlert();

  return (
    <ContractOrderAlertDialog
      isOpen={isOpen}
      alertData={alertData}
      isDismissing={isDismissing}
      onDismiss={dismiss}
      onViewOrders={viewOrders}
    />
  );
}
