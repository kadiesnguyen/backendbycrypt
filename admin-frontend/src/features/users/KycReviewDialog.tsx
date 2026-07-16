"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { ActionButton } from "@/components/actions";
import { useI18n } from "@/lib/i18n/useI18n";
import { fetchKycForm } from "./api";

type KycReviewDialogProps = {
  userId: number;
  onClose: () => void;
  onReview: (payload: { rzstatus: 2 | 3; username: string; kjid?: number }) => Promise<void>;
  isPending?: boolean;
  error?: string | null;
};

function formatTime(ts: number): string {
  if (!ts) return "—";
  return new Date(ts * 1000).toLocaleString("vi-VN");
}

export function KycReviewDialog({
  userId,
  onClose,
  onReview,
  isPending,
  error,
}: KycReviewDialogProps) {
  const { t } = useI18n();
  const [kjid, setKjid] = useState<number | "">("");

  const { data, isLoading, isError, error: loadError } = useQuery({
    queryKey: ["admin", "kyc-form", userId],
    queryFn: async () => {
      const res = await fetchKycForm(userId);
      return res.data;
    },
  });

  const miners = data?.miners ?? [];

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
      <div className="flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg border border-border bg-surface">
        <div className="border-b border-border px-6 py-4">
          <h2 className="text-lg font-semibold text-foreground">{t("kyc.reviewTitle")}</h2>
          {data?.username ? (
            <p className="mt-1 text-sm text-muted">{data.username}</p>
          ) : null}
        </div>

        <div className="space-y-4 overflow-y-auto px-6 py-4">
          {error ? <p className="text-sm text-danger">{error}</p> : null}
          {isLoading ? <p className="text-sm text-muted">{t("common.updating")}</p> : null}
          {isError ? (
            <p className="text-sm text-danger">
              {loadError instanceof Error ? loadError.message : t("common.loadFailed")}
            </p>
          ) : null}

          {data ? (
            <>
              <div>
                <p className="text-sm font-medium text-foreground">{t("userForm.cccd")}</p>
                <p className="mt-1 text-sm text-muted">{data.cccd || "—"}</p>
              </div>

              <div>
                <p className="text-sm font-medium text-foreground">{t("kyc.submittedAt")}</p>
                <p className="mt-1 text-sm text-muted">{formatTime(data.rztime)}</p>
              </div>

              <div className="grid gap-4 sm:grid-cols-2">
                <div>
                  <p className="mb-2 text-sm font-medium text-foreground">{t("kyc.idFront")}</p>
                  {data.cardfm ? (
                    <a href={data.cardfm} target="_blank" rel="noopener noreferrer" className="block">
                      {/* eslint-disable-next-line @next/next/no-img-element */}
                      <img
                        src={data.cardfm}
                        alt={t("kyc.idFront")}
                        className="max-h-40 rounded border border-border object-contain"
                      />
                    </a>
                  ) : (
                    <p className="text-sm text-muted">—</p>
                  )}
                </div>
                <div>
                  <p className="mb-2 text-sm font-medium text-foreground">{t("kyc.idBack")}</p>
                  {data.cardzm ? (
                    <a href={data.cardzm} target="_blank" rel="noopener noreferrer" className="block">
                      {/* eslint-disable-next-line @next/next/no-img-element */}
                      <img
                        src={data.cardzm}
                        alt={t("kyc.idBack")}
                        className="max-h-40 rounded border border-border object-contain"
                      />
                    </a>
                  ) : (
                    <p className="text-sm text-muted">—</p>
                  )}
                </div>
              </div>

              {miners.length > 0 ? (
                <div>
                  <label htmlFor="kyc-miner" className="block text-sm font-medium text-foreground">
                    {t("kyc.rewardMiner")}
                  </label>
                  <select
                    id="kyc-miner"
                    required
                    value={kjid}
                    onChange={(e) => setKjid(e.target.value ? Number(e.target.value) : "")}
                    className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
                  >
                    <option value="">{t("kyc.selectMiner")}</option>
                    {miners.map((m) => (
                      <option key={m.id} value={m.id}>
                        {m.title}
                      </option>
                    ))}
                  </select>
                </div>
              ) : (
                <p className="rounded-lg border border-border bg-surface-elevated px-3 py-2 text-sm text-muted">
                  {t("kyc.noRewardMiner")}
                </p>
              )}
            </>
          ) : null}
        </div>

        <div className="flex justify-end gap-2 border-t border-border px-6 py-4">
          <ActionButton variant="ghost" type="button" onClick={onClose}>
            {t("common.cancel")}
          </ActionButton>
          {data ? (
            <>
              <ActionButton
                variant="danger"
                disabled={isPending}
                onClick={() => onReview({ rzstatus: 3, username: data.username })}
              >
                {t("action.reject")}
              </ActionButton>
              <ActionButton
                variant="success"
                disabled={isPending || (miners.length > 0 && kjid === "")}
                onClick={() =>
                  onReview({
                    rzstatus: 2,
                    username: data.username,
                    ...(kjid !== "" ? { kjid: Number(kjid) } : {}),
                  })
                }
              >
                {t("action.approve")}
              </ActionButton>
            </>
          ) : null}
        </div>
      </div>
    </div>
  );
}
