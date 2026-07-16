"use client";

import { useEffect, useRef, useState } from "react";
import type { AdminDepositPort, DepositPortFormMeta, DepositPortUpsertPayload } from "./types";

const inputClass =
  "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

type DepositPortFormDialogProps = {
  isOpen: boolean;
  editingItem: AdminDepositPort | null;
  formMeta: DepositPortFormMeta | undefined;
  isLoadingDetail: boolean;
  isPending: boolean;
  error: string | null;
  onSubmit: (payload: DepositPortUpsertPayload) => void;
  onClose: () => void;
};

export function DepositPortFormDialog({
  isOpen,
  editingItem,
  formMeta,
  isLoadingDetail,
  isPending,
  error,
  onSubmit,
  onClose,
}: DepositPortFormDialogProps) {
  const nameRef = useRef<HTMLInputElement>(null);
  const [name, setName] = useState("");
  const [wallet, setWallet] = useState("");
  const [address, setAddress] = useState("");
  const [coin, setCoin] = useState("");
  const [status, setStatus] = useState("1");

  const isEdit = editingItem !== null;

  useEffect(() => {
    if (!isOpen) return;

    if (editingItem) {
      setName(editingItem.name);
      setWallet(editingItem.wallet ?? "");
      setAddress(editingItem.address ?? "");
      setCoin(editingItem.coin ?? "");
      setStatus(String(editingItem.status));
    } else {
      setName("");
      setWallet("");
      setAddress("");
      setCoin("");
      setStatus("1");
    }
  }, [isOpen, editingItem]);

  useEffect(() => {
    if (isOpen) {
      nameRef.current?.focus();
    }
  }, [isOpen]);

  useEffect(() => {
    if (!isOpen) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && !isPending) {
        onClose();
      }
    };

    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, isPending, onClose]);

  if (!isOpen) return null;

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit({
      name: name.trim(),
      wallet: wallet.trim() || undefined,
      address: address.trim() || undefined,
      coin: coin.trim() || undefined,
      status: Number(status),
    });
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" role="presentation">
      <button
        type="button"
        aria-label="Close dialog backdrop"
        className="absolute inset-0 bg-background/80"
        onClick={isPending ? undefined : onClose}
        disabled={isPending}
      />
      <dialog
        open
        aria-labelledby="deposit-port-form-title"
        className="relative z-10 flex max-h-[90vh] w-full max-w-lg flex-col rounded-lg border border-border bg-surface shadow-lg"
      >
        <form onSubmit={handleSubmit} className="flex max-h-[90vh] flex-col">
          <div className="border-b border-border px-6 py-4">
            <h2 id="deposit-port-form-title" className="text-lg font-semibold text-foreground">
              {isEdit ? "Edit deposit port" : "Create deposit port"}
            </h2>
          </div>

          <div className="flex-1 space-y-4 overflow-y-auto px-6 py-4">
            {error ? (
              <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
                {error}
              </div>
            ) : null}

            {isLoadingDetail ? (
              <p className="text-sm text-muted" role="status">
                Loading deposit port details…
              </p>
            ) : (
              <>
                <div>
                  <label htmlFor="port-name" className="block text-sm font-medium text-foreground">
                    Name
                  </label>
                  <input
                    ref={nameRef}
                    id="port-name"
                    type="text"
                    required
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className={inputClass}
                  />
                </div>
                <div>
                  <label htmlFor="port-coin" className="block text-sm font-medium text-foreground">
                    Coin
                  </label>
                  <select
                    id="port-coin"
                    value={coin}
                    onChange={(e) => setCoin(e.target.value)}
                    className={inputClass}
                  >
                    <option value="">— Select coin —</option>
                    {formMeta?.coins.map((c) => (
                      <option key={c.id} value={c.name}>
                        {c.title ? `${c.title} (${c.name})` : c.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label htmlFor="port-wallet" className="block text-sm font-medium text-foreground">
                    Wallet type
                  </label>
                  <input
                    id="port-wallet"
                    type="text"
                    value={wallet}
                    onChange={(e) => setWallet(e.target.value)}
                    placeholder="e.g. TRC20"
                    className={inputClass}
                  />
                </div>
                <div>
                  <label htmlFor="port-address" className="block text-sm font-medium text-foreground">
                    Deposit address
                  </label>
                  <input
                    id="port-address"
                    type="text"
                    value={address}
                    onChange={(e) => setAddress(e.target.value)}
                    className={inputClass}
                  />
                </div>
                <div>
                  <label htmlFor="port-status" className="block text-sm font-medium text-foreground">
                    Status
                  </label>
                  <select
                    id="port-status"
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    className={inputClass}
                  >
                    <option value="1">Enabled</option>
                    <option value="0">Disabled</option>
                  </select>
                </div>
              </>
            )}
          </div>

          <div className="flex justify-end gap-3 border-t border-border px-6 py-4">
            <button
              type="button"
              disabled={isPending}
              onClick={onClose}
              className="rounded border border-border px-4 py-2 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={isPending || isLoadingDetail}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
            >
              {isPending ? "Saving…" : isEdit ? "Save changes" : "Create port"}
            </button>
          </div>
        </form>
      </dialog>
    </div>
  );
}
