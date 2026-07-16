"use client";

import { useEffect, useRef, useState } from "react";
import type { AdminCoin, CoinUpsertPayload } from "./types";

const inputClass =
  "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

type CoinFormDialogProps = {
  isOpen: boolean;
  editingItem: AdminCoin | null;
  isLoadingDetail: boolean;
  isPending: boolean;
  error: string | null;
  onSubmit: (payload: CoinUpsertPayload) => void;
  onClose: () => void;
};

export function CoinFormDialog({
  isOpen,
  editingItem,
  isLoadingDetail,
  isPending,
  error,
  onSubmit,
  onClose,
}: CoinFormDialogProps) {
  const nameRef = useRef<HTMLInputElement>(null);
  const [name, setName] = useState("");
  const [title, setTitle] = useState("");
  const [type, setType] = useState("1");
  const [sort, setSort] = useState("0");
  const [status, setStatus] = useState("1");
  const [czline, setCzline] = useState("");
  const [czaddress, setCzaddress] = useState("");
  const [czstatus, setCzstatus] = useState("1");
  const [czminnum, setCzminnum] = useState("");
  const [txstatus, setTxstatus] = useState("1");
  const [txminnum, setTxminnum] = useState("");
  const [txmaxnum, setTxmaxnum] = useState("");
  const [txsxf, setTxsxf] = useState("");
  const [bbsxf, setBbsxf] = useState("");
  const [hysxf, setHysxf] = useState("");

  const isEdit = editingItem !== null;

  useEffect(() => {
    if (!isOpen) return;

    if (editingItem) {
      setName(editingItem.name);
      setTitle(editingItem.title ?? "");
      setType(String(editingItem.type));
      setSort(String(editingItem.sort));
      setStatus(editingItem.status === 1 ? "1" : "2");
      setCzline(editingItem.czline ?? "");
      setCzaddress(editingItem.czaddress ?? "");
      setCzstatus(String(editingItem.czstatus));
      setCzminnum(editingItem.czminnum ?? "");
      setTxstatus(String(editingItem.txstatus));
      setTxminnum(editingItem.txminnum ?? "");
      setTxmaxnum(editingItem.txmaxnum ?? "");
      setTxsxf(editingItem.txsxf ?? "");
      setBbsxf(editingItem.bbsxf ?? "");
      setHysxf(editingItem.hysxf ?? "");
    } else {
      setName("");
      setTitle("");
      setType("1");
      setSort("0");
      setStatus("1");
      setCzline("");
      setCzaddress("");
      setCzstatus("1");
      setCzminnum("");
      setTxstatus("1");
      setTxminnum("");
      setTxmaxnum("");
      setTxsxf("");
      setBbsxf("");
      setHysxf("");
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
    const payload: CoinUpsertPayload = {
      title: title.trim() || undefined,
      type: Number(type),
      sort: Number(sort),
      status: Number(status),
      czline: czline.trim() || undefined,
      czaddress: czaddress.trim() || undefined,
      czstatus: Number(czstatus),
      czminnum: czminnum.trim() || undefined,
      txstatus: Number(txstatus),
      txminnum: txminnum.trim() || undefined,
      txmaxnum: txmaxnum.trim() || undefined,
      txsxf: txsxf.trim() || undefined,
      bbsxf: bbsxf.trim() || undefined,
      hysxf: hysxf.trim() || undefined,
    };

    if (!isEdit) {
      payload.name = name.trim().toLowerCase();
    }

    onSubmit(payload);
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
        aria-labelledby="coin-form-title"
        className="relative z-10 flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg border border-border bg-surface shadow-lg"
      >
        <form onSubmit={handleSubmit} className="flex max-h-[90vh] flex-col">
          <div className="border-b border-border px-6 py-4">
            <h2 id="coin-form-title" className="text-lg font-semibold text-foreground">
              {isEdit ? "Edit coin" : "Create coin"}
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
                Loading coin details…
              </p>
            ) : (
              <>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label htmlFor="coin-name" className="block text-sm font-medium text-foreground">
                      Symbol {isEdit ? "" : "(lowercase)"}
                    </label>
                    <input
                      ref={nameRef}
                      id="coin-name"
                      type="text"
                      required={!isEdit}
                      disabled={isEdit}
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      placeholder="e.g. usdt"
                      className={inputClass}
                    />
                  </div>
                  <div>
                    <label htmlFor="coin-title" className="block text-sm font-medium text-foreground">
                      Display title
                    </label>
                    <input
                      id="coin-title"
                      type="text"
                      value={title}
                      onChange={(e) => setTitle(e.target.value)}
                      className={inputClass}
                    />
                  </div>
                  <div>
                    <label htmlFor="coin-type" className="block text-sm font-medium text-foreground">
                      Type
                    </label>
                    <select
                      id="coin-type"
                      value={type}
                      onChange={(e) => setType(e.target.value)}
                      className={inputClass}
                    >
                      <option value="1">Crypto</option>
                      <option value="2">Fiat</option>
                      <option value="3">Other</option>
                    </select>
                  </div>
                  <div>
                    <label htmlFor="coin-sort" className="block text-sm font-medium text-foreground">
                      Sort order
                    </label>
                    <input
                      id="coin-sort"
                      type="number"
                      value={sort}
                      onChange={(e) => setSort(e.target.value)}
                      className={inputClass}
                    />
                  </div>
                  <div>
                    <label htmlFor="coin-status" className="block text-sm font-medium text-foreground">
                      Status
                    </label>
                    <select
                      id="coin-status"
                      value={status}
                      onChange={(e) => setStatus(e.target.value)}
                      className={inputClass}
                    >
                      <option value="1">Enabled</option>
                      <option value="2">Disabled</option>
                    </select>
                  </div>
                </div>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">Deposit settings</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="coin-czline" className="block text-sm font-medium text-foreground">
                        Chain / line
                      </label>
                      <input
                        id="coin-czline"
                        type="text"
                        value={czline}
                        onChange={(e) => setCzline(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-czaddress" className="block text-sm font-medium text-foreground">
                        Deposit address
                      </label>
                      <input
                        id="coin-czaddress"
                        type="text"
                        value={czaddress}
                        onChange={(e) => setCzaddress(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-czstatus" className="block text-sm font-medium text-foreground">
                        Deposit status
                      </label>
                      <select
                        id="coin-czstatus"
                        value={czstatus}
                        onChange={(e) => setCzstatus(e.target.value)}
                        className={inputClass}
                      >
                        <option value="1">Open</option>
                        <option value="2">Closed</option>
                      </select>
                    </div>
                    <div>
                      <label htmlFor="coin-czminnum" className="block text-sm font-medium text-foreground">
                        Min deposit
                      </label>
                      <input
                        id="coin-czminnum"
                        type="text"
                        value={czminnum}
                        onChange={(e) => setCzminnum(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                  </div>
                </fieldset>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">Withdrawal & fees</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="coin-txstatus" className="block text-sm font-medium text-foreground">
                        Withdrawal status
                      </label>
                      <select
                        id="coin-txstatus"
                        value={txstatus}
                        onChange={(e) => setTxstatus(e.target.value)}
                        className={inputClass}
                      >
                        <option value="1">Open</option>
                        <option value="2">Closed</option>
                      </select>
                    </div>
                    <div>
                      <label htmlFor="coin-txminnum" className="block text-sm font-medium text-foreground">
                        Min withdrawal
                      </label>
                      <input
                        id="coin-txminnum"
                        type="text"
                        value={txminnum}
                        onChange={(e) => setTxminnum(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-txmaxnum" className="block text-sm font-medium text-foreground">
                        Max withdrawal
                      </label>
                      <input
                        id="coin-txmaxnum"
                        type="text"
                        value={txmaxnum}
                        onChange={(e) => setTxmaxnum(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-txsxf" className="block text-sm font-medium text-foreground">
                        Withdrawal fee
                      </label>
                      <input
                        id="coin-txsxf"
                        type="text"
                        value={txsxf}
                        onChange={(e) => setTxsxf(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-bbsxf" className="block text-sm font-medium text-foreground">
                        Spot fee (bbsxf)
                      </label>
                      <input
                        id="coin-bbsxf"
                        type="text"
                        value={bbsxf}
                        onChange={(e) => setBbsxf(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                    <div>
                      <label htmlFor="coin-hysxf" className="block text-sm font-medium text-foreground">
                        Contract fee (hysxf)
                      </label>
                      <input
                        id="coin-hysxf"
                        type="text"
                        value={hysxf}
                        onChange={(e) => setHysxf(e.target.value)}
                        className={inputClass}
                      />
                    </div>
                  </div>
                </fieldset>
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
              {isPending ? "Saving…" : isEdit ? "Save changes" : "Create coin"}
            </button>
          </div>
        </form>
      </dialog>
    </div>
  );
}
