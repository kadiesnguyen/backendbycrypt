"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import { ActionButton } from "@/components/actions";
import { PageHeader } from "@/components/list/ListPageParts";
import { changePassword } from "@/features/auth/api";
import { clearAdminToken } from "@/lib/api-client";
import { useI18n } from "@/lib/i18n/useI18n";

const inputClass =
  "mt-1 w-full max-w-md rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

export function ChangePasswordContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const [oldPassword, setOldPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [isPending, setIsPending] = useState(false);

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);

    if (newPassword !== confirmPassword) {
      setError(t("auth.passwordMismatch"));
      return;
    }

    setIsPending(true);
    try {
      const res = await changePassword({
        old_password: oldPassword,
        new_password: newPassword,
        confirm_password: confirmPassword,
      });
      setSuccess(res.message || t("auth.passwordChanged"));
      clearAdminToken();
      window.setTimeout(() => router.replace("/login"), 1500);
    } catch (err) {
      setError(err instanceof Error ? err.message : t("common.actionFailed"));
    } finally {
      setIsPending(false);
    }
  };

  return (
    <div className="space-y-6">
      <PageHeader titleKey="auth.changePasswordTitle" descriptionKey="auth.changePasswordDescription" />

      <form
        onSubmit={handleSubmit}
        className="max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6"
      >
        {error ? <p className="text-sm text-danger">{error}</p> : null}
        {success ? <p className="text-sm text-success">{success}</p> : null}

        <div>
          <label htmlFor="old-password" className="block text-sm font-medium text-foreground">
            {t("auth.oldPassword")}
          </label>
          <input
            id="old-password"
            type="password"
            required
            autoComplete="current-password"
            value={oldPassword}
            onChange={(e) => setOldPassword(e.target.value)}
            className={inputClass}
          />
        </div>

        <div>
          <label htmlFor="new-password" className="block text-sm font-medium text-foreground">
            {t("auth.newPassword")}
          </label>
          <input
            id="new-password"
            type="password"
            required
            minLength={6}
            autoComplete="new-password"
            value={newPassword}
            onChange={(e) => setNewPassword(e.target.value)}
            className={inputClass}
          />
        </div>

        <div>
          <label htmlFor="confirm-password" className="block text-sm font-medium text-foreground">
            {t("auth.confirmPassword")}
          </label>
          <input
            id="confirm-password"
            type="password"
            required
            minLength={6}
            autoComplete="new-password"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
            className={inputClass}
          />
        </div>

        <ActionButton variant="primary" type="submit" disabled={isPending}>
          {isPending ? t("common.updating") : t("common.confirm")}
        </ActionButton>
      </form>
    </div>
  );
}
