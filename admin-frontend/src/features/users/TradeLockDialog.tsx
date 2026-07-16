"use client";

import { ActionButton } from "@/components/actions";
import { useI18n } from "@/lib/i18n/useI18n";

export const DEFAULT_TRADE_LOCK_MESSAGE = "Tài khoản đang bị khóa giao dịch";

type TradeLockDialogProps = {
  username: string;
  message: string;
  onMessageChange: (value: string) => void;
  onClose: () => void;
  onConfirm: () => Promise<void>;
  isPending?: boolean;
  error?: string | null;
};

export function TradeLockDialog({
  username,
  message,
  onMessageChange,
  onClose,
  onConfirm,
  isPending,
  error,
}: TradeLockDialogProps) {
  const { t } = useI18n();

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
      <form
        onSubmit={async (e) => {
          e.preventDefault();
          await onConfirm();
        }}
        className="w-full max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6"
      >
        <h2 className="text-lg font-semibold text-foreground">
          {t("action.lockTrade")} — {username}
        </h2>
        <p className="text-sm text-muted">{t("page.users.tradeLockHint")}</p>
        {error ? <p className="text-sm text-danger">{error}</p> : null}
        <textarea
          required
          rows={4}
          value={message}
          onChange={(e) => onMessageChange(e.target.value)}
          className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
        />
        <div className="flex justify-end gap-2">
          <ActionButton variant="ghost" type="button" onClick={onClose}>
            {t("common.cancel")}
          </ActionButton>
          <ActionButton variant="warning" type="submit" disabled={isPending}>
            {t("common.confirm")}
          </ActionButton>
        </div>
      </form>
    </div>
  );
}
