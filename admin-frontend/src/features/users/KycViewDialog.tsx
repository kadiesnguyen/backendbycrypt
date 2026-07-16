"use client";

import { useQuery } from "@tanstack/react-query";
import { ActionButton } from "@/components/actions";
import { useI18n } from "@/lib/i18n/useI18n";
import { fetchUser } from "./api";

type KycViewDialogProps = {
  userId: number;
  onClose: () => void;
};

function formatTime(ts: number): string {
  if (!ts) return "—";
  return new Date(ts * 1000).toLocaleString("vi-VN");
}

export function KycViewDialog({ userId, onClose }: KycViewDialogProps) {
  const { t } = useI18n();

  const { data, isLoading, isError, error } = useQuery({
    queryKey: ["admin", "user", userId, "kyc-view"],
    queryFn: async () => {
      const res = await fetchUser(userId);
      return res.data;
    },
  });

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
      <div className="flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg border border-border bg-surface">
        <div className="border-b border-border px-6 py-4">
          <h2 className="text-lg font-semibold text-foreground">{t("kyc.viewTitle")}</h2>
          {data?.username ? <p className="mt-1 text-sm text-muted">{data.username}</p> : null}
        </div>

        <div className="space-y-4 overflow-y-auto px-6 py-4">
          {isLoading ? <p className="text-sm text-muted">{t("common.updating")}</p> : null}
          {isError ? (
            <p className="text-sm text-danger">
              {error instanceof Error ? error.message : t("common.loadFailed")}
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
                <p className="mt-1 text-sm text-muted">{formatTime(data.rztime ?? 0)}</p>
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
            </>
          ) : null}
        </div>

        <div className="flex justify-end border-t border-border px-6 py-4">
          <ActionButton variant="ghost" type="button" onClick={onClose}>
            {t("common.cancel")}
          </ActionButton>
        </div>
      </div>
    </div>
  );
}
