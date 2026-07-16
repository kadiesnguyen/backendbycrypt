"use client";

import { useEffect, useRef, useState } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import { stakeImageUrl, uploadStakeImage } from "./api";
import type { AdminStake, StakeUpsertPayload } from "./types";

const inputClass =
  "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

type StakeFormDialogProps = {
  isOpen: boolean;
  editingItem: AdminStake | null;
  isLoadingDetail: boolean;
  isPending: boolean;
  error: string | null;
  onSubmit: (payload: StakeUpsertPayload) => void;
  onClose: () => void;
};

export function StakeFormDialog({
  isOpen,
  editingItem,
  isLoadingDetail,
  isPending,
  error,
  onSubmit,
  onClose,
}: StakeFormDialogProps) {
  const { t } = useI18n();
  const nameRef = useRef<HTMLInputElement>(null);
  const fileRef = useRef<HTMLInputElement>(null);

  const [name, setName] = useState("");
  const [min, setMin] = useState("");
  const [max, setMax] = useState("");
  const [open, setOpen] = useState("1");
  const [percent, setPercent] = useState("");
  const [imgs, setImgs] = useState("");
  const [content, setContent] = useState("");
  const [status, setStatus] = useState("1");
  const [state, setState] = useState("1");
  const [uploadError, setUploadError] = useState<string | null>(null);
  const [isUploading, setIsUploading] = useState(false);

  const isEdit = editingItem !== null;

  useEffect(() => {
    if (!isOpen) return;

    if (editingItem) {
      setName(editingItem.name);
      setMin(editingItem.min);
      setMax(editingItem.max);
      setOpen(String(editingItem.open));
      setPercent(editingItem.percent);
      setImgs(editingItem.imgs ?? "");
      setContent(editingItem.content ?? "");
      setStatus(String(editingItem.status));
      setState(String(editingItem.state));
    } else {
      setName("");
      setMin("");
      setMax("");
      setOpen("1");
      setPercent("");
      setImgs("");
      setContent("");
      setStatus("1");
      setState("1");
    }
    setUploadError(null);
  }, [isOpen, editingItem]);

  useEffect(() => {
    if (isOpen) nameRef.current?.focus();
  }, [isOpen]);

  useEffect(() => {
    if (!isOpen) return;
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && !isPending) onClose();
    };
    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, isPending, onClose]);

  if (!isOpen) return null;

  const handleImageChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploadError(null);
    setIsUploading(true);
    try {
      const result = await uploadStakeImage(file);
      setImgs(result.data.path);
    } catch (err) {
      setUploadError(err instanceof Error ? err.message : t("stakeForm.uploadFailed"));
    } finally {
      setIsUploading(false);
      e.target.value = "";
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit({
      name: name.trim(),
      min: min.trim(),
      max: max.trim(),
      open: Number(open),
      percent: percent.trim(),
      imgs: imgs.trim() || undefined,
      content: content.trim() || undefined,
      status: Number(status),
      state: Number(state),
    });
  };

  const previewUrl = stakeImageUrl(imgs);

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" role="presentation">
      <button
        type="button"
        aria-label={t("common.cancel")}
        className="absolute inset-0 bg-background/80"
        onClick={isPending ? undefined : onClose}
        disabled={isPending}
      />
      <dialog
        open
        aria-labelledby="stake-form-title"
        className="relative z-10 flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg border border-border bg-surface shadow-lg"
      >
        <form onSubmit={handleSubmit} className="flex max-h-[90vh] flex-col">
          <div className="border-b border-border px-6 py-4">
            <h2 id="stake-form-title" className="text-lg font-semibold text-foreground">
              {isEdit ? t("stakeForm.editTitle") : t("stakeForm.createTitle")}
            </h2>
          </div>

          <div className="flex-1 space-y-4 overflow-y-auto px-6 py-4">
            {error ? (
              <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
                {error}
              </div>
            ) : null}

            {isLoadingDetail ? (
              <p className="text-sm text-muted" role="status">{t("stakeForm.loading")}</p>
            ) : (
              <div className="grid gap-4 sm:grid-cols-2">
                <div className="sm:col-span-2">
                  <label htmlFor="stake-name" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.name")}
                  </label>
                  <input
                    ref={nameRef}
                    id="stake-name"
                    required
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div>
                  <label htmlFor="stake-min" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.minAmount")}
                  </label>
                  <input
                    id="stake-min"
                    required
                    inputMode="decimal"
                    value={min}
                    onChange={(e) => setMin(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div>
                  <label htmlFor="stake-max" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.maxAmount")}
                  </label>
                  <input
                    id="stake-max"
                    required
                    inputMode="decimal"
                    value={max}
                    onChange={(e) => setMax(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div>
                  <label htmlFor="stake-open" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.days")}
                  </label>
                  <input
                    id="stake-open"
                    type="number"
                    min={1}
                    required
                    value={open}
                    onChange={(e) => setOpen(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div>
                  <label htmlFor="stake-percent" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.rate")}
                  </label>
                  <input
                    id="stake-percent"
                    required
                    inputMode="decimal"
                    value={percent}
                    onChange={(e) => setPercent(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div>
                  <label htmlFor="stake-status" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.display")}
                  </label>
                  <select id="stake-status" value={status} onChange={(e) => setStatus(e.target.value)} className={inputClass}>
                    <option value="1">{t("label.stake.display.1")}</option>
                    <option value="2">{t("label.stake.display.2")}</option>
                  </select>
                </div>

                <div>
                  <label htmlFor="stake-state" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.state")}
                  </label>
                  <select id="stake-state" value={state} onChange={(e) => setState(e.target.value)} className={inputClass}>
                    <option value="1">{t("label.stake.state.1")}</option>
                    <option value="2">{t("label.stake.state.2")}</option>
                  </select>
                </div>

                <div className="sm:col-span-2">
                  <label htmlFor="stake-content" className="block text-sm font-medium text-foreground">
                    {t("stakeForm.content")}
                  </label>
                  <textarea
                    id="stake-content"
                    rows={4}
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                    className={inputClass}
                  />
                </div>

                <div className="sm:col-span-2">
                  <span className="block text-sm font-medium text-foreground">{t("stakeForm.image")}</span>
                  <div className="mt-2 flex items-center gap-4">
                    {previewUrl ? (
                      <img src={previewUrl} alt="" className="h-16 w-16 rounded border border-border object-cover" />
                    ) : (
                      <div className="flex h-16 w-16 items-center justify-center rounded border border-dashed border-border text-xs text-muted">
                        —
                      </div>
                    )}
                    <div>
                      <input ref={fileRef} type="file" accept="image/*" className="hidden" onChange={handleImageChange} />
                      <button
                        type="button"
                        disabled={isUploading || isPending}
                        onClick={() => fileRef.current?.click()}
                        className="rounded border border-border px-3 py-1.5 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                      >
                        {isUploading ? t("stakeForm.uploading") : t("stakeForm.uploadImage")}
                      </button>
                      {uploadError ? <p className="mt-1 text-xs text-danger">{uploadError}</p> : null}
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>

          <div className="flex justify-end gap-3 border-t border-border px-6 py-4">
            <button
              type="button"
              disabled={isPending}
              onClick={onClose}
              className="rounded border border-border px-4 py-2 text-sm text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
            >
              {t("common.cancel")}
            </button>
            <button
              type="submit"
              disabled={isPending || isLoadingDetail || isUploading}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
            >
              {isPending ? t("common.saving") : isEdit ? t("common.save") : t("common.create")}
            </button>
          </div>
        </form>
      </dialog>
    </div>
  );
}
