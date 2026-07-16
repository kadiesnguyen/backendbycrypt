"use client";

import { useEffect, useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { ActionButton } from "@/components/actions";
import { useI18n } from "@/lib/i18n/useI18n";
import { fetchUser } from "./api";
import type { UserUpsertPayload } from "./api";
import type { AdminUser } from "./types";

type UserFormDialogProps = {
  mode: "create" | "edit";
  userId?: number;
  onClose: () => void;
  onSubmit: (payload: UserUpsertPayload) => Promise<void>;
  isPending?: boolean;
  error?: string | null;
};

const inputClass =
  "w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

function Field({
  label,
  hint,
  children,
}: {
  label: string;
  hint?: string;
  children: React.ReactNode;
}) {
  return (
    <div className="space-y-1">
      <label className="block text-sm font-medium text-foreground">{label}</label>
      {children}
      {hint ? <p className="text-xs text-danger">{hint}</p> : null}
    </div>
  );
}

/** ponytail: on edit send "" so API clears DB columns; on create omit empty keys */
function payloadStr(mode: "create" | "edit", value: string): string | undefined {
  return mode === "edit" ? value : value || undefined;
}

function userToForm(user: AdminUser) {
  return {
    username: user.username ?? "",
    password: "",
    paypassword: "",
    invit_1: String(user.invit_1 ?? ""),
    invit_2: String(user.invit_2 ?? ""),
    invit_3: String(user.invit_3 ?? ""),
    invit: user.invit ?? "",
    status: user.status ?? 2,
    txstate: user.txstate ?? 1,
    hy_result_mode: user.hy_result_mode ?? 0,
    kefu: String(user.kefu ?? ""),
    bank_name: user.bank_name ?? "",
    bank_acc_no: user.bank_acc_no ?? "",
    bank_acc_name: user.bank_acc_name ?? "",
    wallet: user.wallet ?? "",
    cccd: user.cccd ?? "",
    phonenumber: user.phonenumber ?? "",
    fullname: user.fullname ?? "",
  };
}

export function UserFormDialog({
  mode,
  userId,
  onClose,
  onSubmit,
  isPending,
  error,
}: UserFormDialogProps) {
  const { t } = useI18n();
  const [form, setForm] = useState(userToForm({ id: 0, username: "", fullname: null, phonenumber: null, status: 2, txstate: 1, level: 0, is_agent: 0, addtime: 0, lgtime: null, login_state: null }));

  const detailQuery = useQuery({
    queryKey: ["admin", "user", userId],
    queryFn: async () => {
      const res = await fetchUser(userId!);
      return res.data;
    },
    enabled: mode === "edit" && userId !== undefined,
  });

  useEffect(() => {
    if (mode === "edit" && detailQuery.data) {
      setForm(userToForm(detailQuery.data));
    }
  }, [mode, detailQuery.data]);

  const set = (key: keyof typeof form, value: string | number) => {
    setForm((prev) => ({ ...prev, [key]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const payload: UserUpsertPayload = {
      username: form.username,
      fullname: payloadStr(mode, form.fullname),
      phonenumber: payloadStr(mode, form.phonenumber),
      cccd: payloadStr(mode, form.cccd),
      status: form.status,
      txstate: form.txstate,
      hy_result_mode: form.hy_result_mode,
      kefu: form.kefu !== "" ? Number(form.kefu) : undefined,
      bank_name: payloadStr(mode, form.bank_name),
      bank_acc_no: payloadStr(mode, form.bank_acc_no),
      bank_acc_name: payloadStr(mode, form.bank_acc_name),
      wallet: payloadStr(mode, form.wallet),
      invit_1: form.invit_1 !== "" ? Number(form.invit_1) : undefined,
      invit_2: form.invit_2 !== "" ? Number(form.invit_2) : undefined,
      invit_3: form.invit_3 !== "" ? Number(form.invit_3) : undefined,
    };
    if (form.password) payload.password = form.password;
    if (form.paypassword) payload.paypassword = form.paypassword;
    if (mode === "create") {
      payload.invit = form.invit || undefined;
      if (!form.password) return;
    }
    await onSubmit(payload);
  };

  const loading = mode === "edit" && detailQuery.isLoading;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
      <form
        onSubmit={handleSubmit}
        className="flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg border border-border bg-surface"
      >
        <div className="border-b border-border px-6 py-4">
          <h2 className="text-lg font-semibold text-foreground">
            {mode === "create" ? t("userForm.createTitle") : t("userForm.editTitle")}
          </h2>
        </div>

        <div className="space-y-4 overflow-y-auto px-6 py-4">
          {error ? <p className="text-sm text-danger">{error}</p> : null}
          {loading ? <p className="text-sm text-muted">{t("common.updating")}</p> : null}

          <Field label={t("common.username")}>
            <input required value={form.username} onChange={(e) => set("username", e.target.value)} className={inputClass} readOnly={mode === "edit"} />
          </Field>

          <div className="grid gap-4 sm:grid-cols-2">
            <Field label={t("userForm.password")} hint={t("userForm.passwordHint")}>
              <input type="password" value={form.password} onChange={(e) => set("password", e.target.value)} className={inputClass} required={mode === "create"} />
            </Field>
            <Field label={t("userForm.paypassword")} hint={t("userForm.passwordHint")}>
              <input type="password" value={form.paypassword} onChange={(e) => set("paypassword", e.target.value)} className={inputClass} />
            </Field>
          </div>

          <div className="grid gap-4 sm:grid-cols-3">
            <Field label={`${t("userForm.referrer")} 1`}>
              <input value={form.invit_1} onChange={(e) => set("invit_1", e.target.value)} className={inputClass} />
            </Field>
            <Field label={`${t("userForm.referrer")} 2`}>
              <input value={form.invit_2} onChange={(e) => set("invit_2", e.target.value)} className={inputClass} />
            </Field>
            <Field label={`${t("userForm.referrer")} 3`}>
              <input value={form.invit_3} onChange={(e) => set("invit_3", e.target.value)} className={inputClass} />
            </Field>
          </div>

          {mode === "create" ? (
            <Field label={t("userForm.inviteCode")} hint={t("userForm.inviteCodeHint")}>
              <input value={form.invit} onChange={(e) => set("invit", e.target.value)} className={inputClass} />
            </Field>
          ) : (
            <Field label={t("common.invite")}>
              <input value={form.invit} className={inputClass} readOnly />
            </Field>
          )}

          <div className="grid gap-4 sm:grid-cols-2">
            <Field label={t("userForm.loginStatus")} hint={t("userForm.loginStatusHint")}>
              <select value={form.status} onChange={(e) => set("status", Number(e.target.value))} className={inputClass}>
                <option value={1}>{t("userForm.statusNormal")}</option>
                <option value={2}>{t("userForm.statusFreeze")}</option>
              </select>
            </Field>
            <Field label={t("userForm.withdrawStatus")} hint={t("userForm.withdrawStatusHint")}>
              <select value={form.txstate} onChange={(e) => set("txstate", Number(e.target.value))} className={inputClass}>
                <option value={1}>{t("userForm.statusNormal")}</option>
                <option value={2}>{t("userForm.statusProhibited")}</option>
              </select>
            </Field>
          </div>

          <Field label={t("userForm.resultMode")} hint={t("userForm.resultModeHint")}>
            <select value={form.hy_result_mode} onChange={(e) => set("hy_result_mode", Number(e.target.value))} className={inputClass}>
              <option value={0}>{t("userForm.resultAuto")}</option>
              <option value={1}>{t("userForm.resultWin")}</option>
              <option value={2}>{t("userForm.resultLoss")}</option>
            </select>
          </Field>

          <Field label={t("userForm.kefu")}>
            <input value={form.kefu} onChange={(e) => set("kefu", e.target.value)} className={inputClass} />
          </Field>

          <Field label={t("userForm.bankName")}>
            <input value={form.bank_name} onChange={(e) => set("bank_name", e.target.value)} className={inputClass} />
          </Field>
          <Field label={t("userForm.bankAccNo")}>
            <input value={form.bank_acc_no} onChange={(e) => set("bank_acc_no", e.target.value)} className={inputClass} />
          </Field>
          <Field label={t("userForm.bankAccName")}>
            <input value={form.bank_acc_name} onChange={(e) => set("bank_acc_name", e.target.value)} className={inputClass} />
          </Field>
          <Field label={t("userForm.wallet")}>
            <input value={form.wallet} onChange={(e) => set("wallet", e.target.value)} className={inputClass} />
          </Field>
          <Field label={t("userForm.phone")}>
            <input value={form.phonenumber} onChange={(e) => set("phonenumber", e.target.value)} className={inputClass} />
          </Field>
          {mode === "edit" ? (
            <Field label={t("userForm.cccd")}>
              <input value={form.cccd} onChange={(e) => set("cccd", e.target.value)} className={inputClass} />
            </Field>
          ) : null}
        </div>

        <div className="flex justify-end gap-2 border-t border-border px-6 py-4">
          <ActionButton variant="ghost" type="button" onClick={onClose}>
            {t("common.cancel")}
          </ActionButton>
          <ActionButton variant="primary" type="submit" disabled={isPending || loading}>
            {t("common.save")}
          </ActionButton>
        </div>
      </form>
    </div>
  );
}
