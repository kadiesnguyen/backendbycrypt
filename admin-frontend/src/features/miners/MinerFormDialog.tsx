"use client";

import { useEffect, useRef, useState } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import { minerImageUrl, uploadMinerImage } from "./api";
import type { AdminMiner, MinerCoinOption, MinerUpsertPayload } from "./types";

const inputClass =
  "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

type MinerFormDialogProps = {
  isOpen: boolean;
  editingItem: AdminMiner | null;
  coins: MinerCoinOption[];
  isLoadingDetail: boolean;
  isPending: boolean;
  error: string | null;
  onSubmit: (payload: MinerUpsertPayload) => void;
  onClose: () => void;
};

function defaultCoin(coins: MinerCoinOption[]): string {
  return coins.find((c) => c.name.toLowerCase() === "usdt")?.name ?? coins[0]?.name ?? "usdt";
}

export function MinerFormDialog({
  isOpen,
  editingItem,
  coins,
  isLoadingDetail,
  isPending,
  error,
  onSubmit,
  onClose,
}: MinerFormDialogProps) {
  const { t } = useI18n();
  const titleRef = useRef<HTMLInputElement>(null);
  const fileRef = useRef<HTMLInputElement>(null);

  const [title, setTitle] = useState("");
  const [rtype, setRtype] = useState("1");
  const [type, setType] = useState("1");
  const [sharebl, setSharebl] = useState("");
  const [content, setContent] = useState("");
  const [imgs, setImgs] = useState("");
  const [outcoin, setOutcoin] = useState("usdt");
  const [outtype, setOuttype] = useState("2");
  const [dayoutnum, setDayoutnum] = useState("");
  const [pricenum, setPricenum] = useState("");
  const [pricecoin, setPricecoin] = useState("usdt");
  const [buymax, setBuymax] = useState("1");
  const [cycle, setCycle] = useState("90");
  const [suanl, setSuanl] = useState("");
  const [allnum, setAllnum] = useState("999");
  const [ycnum, setYcnum] = useState("0");
  const [jlcoin, setJlcoin] = useState("usdt");
  const [jlnum, setJlnum] = useState("0");
  const [buyask, setBuyask] = useState("1");
  const [asknum, setAsknum] = useState("0");
  const [djout, setDjout] = useState("1");
  const [djday, setDjday] = useState("0");
  const [uploadError, setUploadError] = useState<string | null>(null);
  const [isUploading, setIsUploading] = useState(false);

  const isEdit = editingItem !== null;
  const coinDefault = defaultCoin(coins);

  useEffect(() => {
    if (!isOpen) return;

    if (editingItem) {
      setTitle(editingItem.title);
      setRtype(String(editingItem.rtype));
      setType(String(editingItem.type));
      setSharebl(editingItem.sharebl ?? "");
      setContent(editingItem.content ?? "");
      setImgs(editingItem.imgs ?? "");
      setOutcoin(editingItem.outcoin);
      setOuttype(String(editingItem.outtype));
      setDayoutnum(editingItem.dayoutnum);
      setPricenum(editingItem.pricenum);
      setPricecoin(editingItem.pricecoin);
      setBuymax(String(editingItem.buymax));
      setCycle(String(editingItem.cycle));
      setSuanl(editingItem.suanl);
      setAllnum(String(editingItem.allnum));
      setYcnum(String(editingItem.ycnum));
      setJlcoin(editingItem.jlcoin);
      setJlnum(editingItem.jlnum);
      setBuyask(String(editingItem.buyask));
      setAsknum(String(editingItem.asknum));
      setDjout(String(editingItem.djout));
      setDjday(String(editingItem.djday));
    } else {
      setTitle("");
      setRtype("1");
      setType("1");
      setSharebl("");
      setContent("");
      setImgs("");
      setOutcoin(coinDefault);
      setOuttype("2");
      setDayoutnum("");
      setPricenum("");
      setPricecoin(coinDefault);
      setBuymax("1");
      setCycle("90");
      setSuanl("");
      setAllnum("999");
      setYcnum("0");
      setJlcoin(coinDefault);
      setJlnum("0");
      setBuyask("1");
      setAsknum("0");
      setDjout("1");
      setDjday("0");
    }
    setUploadError(null);
  }, [isOpen, editingItem, coinDefault]);

  useEffect(() => {
    if (isOpen) titleRef.current?.focus();
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
      const result = await uploadMinerImage(file);
      setImgs(result.data.path);
    } catch (err) {
      setUploadError(err instanceof Error ? err.message : t("minerForm.uploadFailed"));
    } finally {
      setIsUploading(false);
      e.target.value = "";
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit({
      title: title.trim(),
      rtype: Number(rtype),
      type: Number(type),
      sharebl: type === "2" ? sharebl.trim() : undefined,
      content: content.trim(),
      imgs: imgs.trim(),
      dayoutnum: dayoutnum.trim(),
      outtype: Number(outtype),
      outcoin: outcoin.trim(),
      pricenum: pricenum.trim(),
      pricecoin: pricecoin.trim(),
      buymax: Number(buymax),
      cycle: Number(cycle),
      suanl: suanl.trim(),
      allnum: Number(allnum),
      ycnum: Number(ycnum),
      jlnum: jlnum.trim(),
      jlcoin: jlcoin.trim(),
      buyask: Number(buyask),
      asknum: Number(asknum),
      djout: Number(djout),
      djday: Number(djday),
    });
  };

  const previewUrl = minerImageUrl(imgs);

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
        aria-labelledby="miner-form-title"
        className="relative z-10 flex max-h-[90vh] w-full max-w-3xl flex-col rounded-lg border border-border bg-surface shadow-lg"
      >
        <form onSubmit={handleSubmit} className="flex max-h-[90vh] flex-col">
          <div className="border-b border-border px-6 py-4">
            <h2 id="miner-form-title" className="text-lg font-semibold text-foreground">
              {isEdit ? t("minerForm.editTitle") : t("minerForm.createTitle")}
            </h2>
          </div>

          <div className="flex-1 space-y-4 overflow-y-auto px-6 py-4">
            {error ? (
              <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
                {error}
              </div>
            ) : null}

            {isLoadingDetail ? (
              <p className="text-sm text-muted" role="status">{t("minerForm.loading")}</p>
            ) : (
              <>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="sm:col-span-2">
                    <label htmlFor="miner-title" className="block text-sm font-medium text-foreground">
                      {t("minerForm.title")}
                    </label>
                    <input
                      ref={titleRef}
                      id="miner-title"
                      required
                      value={title}
                      onChange={(e) => setTitle(e.target.value)}
                      className={inputClass}
                    />
                  </div>

                  <div>
                    <label htmlFor="miner-rtype" className="block text-sm font-medium text-foreground">
                      {t("minerForm.purchaseType")}
                    </label>
                    <select id="miner-rtype" value={rtype} onChange={(e) => setRtype(e.target.value)} className={inputClass}>
                      <option value="1">{t("minerForm.purchaseSell")}</option>
                      <option value="2">{t("minerForm.purchaseOwn")}</option>
                    </select>
                  </div>

                  <div>
                    <label htmlFor="miner-type" className="block text-sm font-medium text-foreground">
                      {t("minerForm.minerType")}
                    </label>
                    <select id="miner-type" value={type} onChange={(e) => setType(e.target.value)} className={inputClass}>
                      <option value="1">{t("minerForm.typeExclusive")}</option>
                      <option value="2">{t("minerForm.typeShared")}</option>
                    </select>
                  </div>

                  {type === "2" ? (
                    <>
                      <div>
                        <label htmlFor="miner-sharebl" className="block text-sm font-medium text-foreground">
                          {t("minerForm.shareRatio")}
                        </label>
                        <input
                          id="miner-sharebl"
                          value={sharebl}
                          onChange={(e) => setSharebl(e.target.value)}
                          className={inputClass}
                        />
                      </div>
                      {editingItem?.sharecode ? (
                        <div>
                          <label className="block text-sm font-medium text-foreground">{t("minerForm.shareCode")}</label>
                          <input readOnly value={editingItem.sharecode} className={`${inputClass} opacity-70`} />
                        </div>
                      ) : null}
                    </>
                  ) : null}

                  <div className="sm:col-span-2">
                    <label htmlFor="miner-content" className="block text-sm font-medium text-foreground">
                      {t("minerForm.info")}
                    </label>
                    <textarea
                      id="miner-content"
                      rows={3}
                      value={content}
                      onChange={(e) => setContent(e.target.value)}
                      className={inputClass}
                    />
                  </div>

                  <div className="sm:col-span-2">
                    <span className="block text-sm font-medium text-foreground">{t("minerForm.image")}</span>
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
                          {isUploading ? t("minerForm.uploading") : t("minerForm.uploadImage")}
                        </button>
                        {uploadError ? <p className="mt-1 text-xs text-danger">{uploadError}</p> : null}
                      </div>
                    </div>
                  </div>
                </div>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">{t("minerForm.sectionOutput")}</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="miner-outcoin" className="block text-sm font-medium text-foreground">{t("minerForm.outCoin")}</label>
                      <select id="miner-outcoin" value={outcoin} onChange={(e) => setOutcoin(e.target.value)} className={inputClass}>
                        {coins.map((c) => (
                          <option key={c.id} value={c.name}>{c.name}{c.title ? ` (${c.title})` : ""}</option>
                        ))}
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-outtype" className="block text-sm font-medium text-foreground">{t("minerForm.outType")}</label>
                      <select id="miner-outtype" value={outtype} onChange={(e) => setOuttype(e.target.value)} className={inputClass}>
                        <option value="1">{t("minerForm.outTypeValue")}</option>
                        <option value="2">{t("minerForm.outTypeToken")}</option>
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-dayoutnum" className="block text-sm font-medium text-foreground">{t("minerForm.dailyOutput")}</label>
                      <input id="miner-dayoutnum" required value={dayoutnum} onChange={(e) => setDayoutnum(e.target.value)} className={inputClass} />
                    </div>
                  </div>
                </fieldset>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">{t("minerForm.sectionPrice")}</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="miner-pricenum" className="block text-sm font-medium text-foreground">{t("minerForm.price")}</label>
                      <input id="miner-pricenum" required value={pricenum} onChange={(e) => setPricenum(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-pricecoin" className="block text-sm font-medium text-foreground">{t("minerForm.priceCoin")}</label>
                      <select id="miner-pricecoin" value={pricecoin} onChange={(e) => setPricecoin(e.target.value)} className={inputClass}>
                        {coins.map((c) => (
                          <option key={c.id} value={c.name}>{c.name}{c.title ? ` (${c.title})` : ""}</option>
                        ))}
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-buymax" className="block text-sm font-medium text-foreground">{t("minerForm.buyMax")}</label>
                      <input id="miner-buymax" type="number" min={0} required value={buymax} onChange={(e) => setBuymax(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-cycle" className="block text-sm font-medium text-foreground">{t("minerForm.cycle")}</label>
                      <input id="miner-cycle" type="number" min={1} required value={cycle} onChange={(e) => setCycle(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-suanl" className="block text-sm font-medium text-foreground">{t("minerForm.power")}</label>
                      <input id="miner-suanl" required value={suanl} onChange={(e) => setSuanl(e.target.value)} className={inputClass} />
                    </div>
                  </div>
                </fieldset>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">{t("minerForm.sectionStock")}</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="miner-allnum" className="block text-sm font-medium text-foreground">{t("minerForm.totalStock")}</label>
                      <input id="miner-allnum" type="number" min={0} required value={allnum} onChange={(e) => setAllnum(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-ycnum" className="block text-sm font-medium text-foreground">{t("minerForm.plannedSales")}</label>
                      <input id="miner-ycnum" type="number" min={0} required value={ycnum} onChange={(e) => setYcnum(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-jlcoin" className="block text-sm font-medium text-foreground">{t("minerForm.rewardCoin")}</label>
                      <select id="miner-jlcoin" value={jlcoin} onChange={(e) => setJlcoin(e.target.value)} className={inputClass}>
                        {coins.map((c) => (
                          <option key={c.id} value={c.name}>{c.name}{c.title ? ` (${c.title})` : ""}</option>
                        ))}
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-jlnum" className="block text-sm font-medium text-foreground">{t("minerForm.rewardAmount")}</label>
                      <input id="miner-jlnum" required value={jlnum} onChange={(e) => setJlnum(e.target.value)} className={inputClass} />
                    </div>
                  </div>
                </fieldset>

                <fieldset className="rounded-lg border border-border p-4">
                  <legend className="px-1 text-sm font-medium text-foreground">{t("minerForm.sectionRules")}</legend>
                  <div className="mt-2 grid gap-4 sm:grid-cols-2">
                    <div>
                      <label htmlFor="miner-buyask" className="block text-sm font-medium text-foreground">{t("minerForm.buyRequirement")}</label>
                      <select id="miner-buyask" value={buyask} onChange={(e) => setBuyask(e.target.value)} className={inputClass}>
                        <option value="1">{t("minerForm.buyByToken")}</option>
                        <option value="2">{t("minerForm.buyByTeam")}</option>
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-asknum" className="block text-sm font-medium text-foreground">{t("minerForm.requirementValue")}</label>
                      <input id="miner-asknum" type="number" min={0} required value={asknum} onChange={(e) => setAsknum(e.target.value)} className={inputClass} />
                    </div>
                    <div>
                      <label htmlFor="miner-djout" className="block text-sm font-medium text-foreground">{t("minerForm.freezeOutput")}</label>
                      <select id="miner-djout" value={djout} onChange={(e) => setDjout(e.target.value)} className={inputClass}>
                        <option value="1">{t("common.no")}</option>
                        <option value="2">{t("common.yes")}</option>
                      </select>
                    </div>
                    <div>
                      <label htmlFor="miner-djday" className="block text-sm font-medium text-foreground">{t("minerForm.freezeDays")}</label>
                      <input id="miner-djday" type="number" min={0} value={djday} onChange={(e) => setDjday(e.target.value)} className={inputClass} />
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
