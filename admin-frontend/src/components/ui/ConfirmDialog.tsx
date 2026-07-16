"use client";

import { useEffect, useRef } from "react";

type ConfirmDialogProps = {
  isOpen: boolean;
  title: string;
  message: string;
  confirmLabel?: string;
  cancelLabel?: string;
  variant?: "default" | "danger";
  isPending?: boolean;
  onConfirm: () => void;
  onCancel: () => void;
};

export function ConfirmDialog({
  isOpen,
  title,
  message,
  confirmLabel = "Confirm",
  cancelLabel = "Cancel",
  variant = "default",
  isPending = false,
  onConfirm,
  onCancel,
}: ConfirmDialogProps) {
  const cancelRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    if (isOpen) {
      cancelRef.current?.focus();
    }
  }, [isOpen]);

  useEffect(() => {
    if (!isOpen) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && !isPending) {
        onCancel();
      }
    };

    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, isPending, onCancel]);

  if (!isOpen) return null;

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center p-4"
      role="presentation"
    >
      <button
        type="button"
        aria-label="Close dialog backdrop"
        className="absolute inset-0 bg-background/80"
        onClick={isPending ? undefined : onCancel}
        disabled={isPending}
      />
      <dialog
        open
        aria-labelledby="confirm-dialog-title"
        aria-describedby="confirm-dialog-message"
        className="relative z-10 w-full max-w-md rounded-lg border border-border bg-surface p-6 shadow-lg"
      >
        <h2 id="confirm-dialog-title" className="text-lg font-semibold text-foreground">
          {title}
        </h2>
        <p id="confirm-dialog-message" className="mt-2 text-sm text-muted">
          {message}
        </p>
        <div className="mt-6 flex justify-end gap-3">
          <button
            ref={cancelRef}
            type="button"
            disabled={isPending}
            onClick={onCancel}
            className="rounded border border-border px-4 py-2 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
          >
            {cancelLabel}
          </button>
          <button
            type="button"
            disabled={isPending}
            onClick={onConfirm}
            className={`rounded px-4 py-2 text-sm font-medium transition disabled:opacity-40 ${
              variant === "danger"
                ? "bg-danger text-foreground hover:opacity-90"
                : "bg-primary text-background hover:opacity-90"
            }`}
          >
            {isPending ? "Processing…" : confirmLabel}
          </button>
        </div>
      </dialog>
    </div>
  );
}
