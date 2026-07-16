"use client";
import { useEffect, useState } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import type { AdminAccount, AdminUpsertPayload } from "./types";

type Props = { isOpen: boolean; editing: AdminAccount | null; isPending: boolean; error: string | null; onSubmit: (p: AdminUpsertPayload) => void; onClose: () => void; };
export function AdminFormDialog({ isOpen, editing, isPending, error, onSubmit, onClose }: Props) {
  const { t } = useI18n();
  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [nickname, setNickname] = useState("");
  const [password, setPassword] = useState("");
  useEffect(() => {
    if (editing) { setUsername(editing.username); setEmail(editing.email ?? ""); setNickname(editing.nickname ?? ""); setPassword(""); }
    else { setUsername(""); setEmail(""); setNickname(""); setPassword(""); }
  }, [editing, isOpen]);
  if (!isOpen) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
      <form onSubmit={(e) => { e.preventDefault(); onSubmit({ username, email, nickname, password: password || undefined }); }} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
        <h2 className="text-lg font-semibold text-foreground">{editing ? t("common.edit") : t("page.admins.create")}</h2>
        {error ? <p className="text-sm text-danger" role="alert">{error}</p> : null}
        <div><label className="block text-sm text-foreground">{t("common.username")}</label><input required value={username} onChange={(e) => setUsername(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
        <div><label className="block text-sm text-foreground">Email</label><input type="email" value={email} onChange={(e) => setEmail(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
        <div><label className="block text-sm text-foreground">Nickname</label><input value={nickname} onChange={(e) => setNickname(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
        <div><label className="block text-sm text-foreground">{t("auth.password")}{editing ? " (optional)" : ""}</label><input type="password" required={!editing} value={password} onChange={(e) => setPassword(e.target.value)} className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" /></div>
        <div className="flex justify-end gap-2">
          <button type="button" onClick={onClose} disabled={isPending} className="rounded border border-border px-4 py-2 text-sm">{t("common.cancel")}</button>
          <button type="submit" disabled={isPending} className="rounded bg-primary px-4 py-2 text-sm text-background">{t("common.save")}</button>
        </div>
      </form>
    </div>
  );
}
