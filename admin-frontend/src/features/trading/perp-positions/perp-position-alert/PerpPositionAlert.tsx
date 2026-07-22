"use client";

import { PerpPositionAlertDialog } from "./PerpPositionAlertDialog";
import { usePerpPositionAlert } from "./usePerpPositionAlert";

export function PerpPositionAlert() {
  const { isOpen, alertData, dismiss, viewPositions, isDismissing } = usePerpPositionAlert();

  return (
    <PerpPositionAlertDialog
      isOpen={isOpen}
      alertData={alertData}
      isDismissing={isDismissing}
      onDismiss={dismiss}
      onViewPositions={viewPositions}
    />
  );
}
