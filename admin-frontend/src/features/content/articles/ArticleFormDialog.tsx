"use client";
import { useEffect, useState } from "react";
import { useI18n } from "@/lib/i18n/useI18n";
import type { Article, ArticleUpsertPayload } from "./types";
type Props = { isOpen: boolean; editing: Article | null; isPending: boolean; error: string | null; onSubmit: (p: ArticleUpsertPayload) => void; onClose: () => void; };
export function ArticleFormDialog({ isOpen, editing, isPending, error, onSubmit, onClose }: Props) {
  const { t } = useI18n();
  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  useEffect(() => { if (editing) { setTitle(editing.title); setContent(editing.content ?? ""); } else { setTitle(""); setContent(""); } }, [editing, isOpen]);
  if (!isOpen) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
      <form onSubmit={(e) => { e.preventDefault(); onSubmit({ title, content, status: 1 }); }} className="w-full max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6">
        <h2 className="text-lg font-semibold">{editing ? t("common.edit") : t("page.articles.create")}</h2>
        {error ? <p className="text-sm text-danger">{error}</p> : null}
        <input required value={title} onChange={(e) => setTitle(e.target.value)} placeholder="Title" className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
        <textarea required rows={5} value={content} onChange={(e) => setContent(e.target.value)} placeholder="Content" className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm" />
        <div className="flex justify-end gap-2"><button type="button" onClick={onClose} disabled={isPending} className="rounded border border-border px-4 py-2 text-sm">{t("common.cancel")}</button><button type="submit" disabled={isPending} className="rounded bg-primary px-4 py-2 text-sm text-background">{t("common.save")}</button></div>
      </form>
    </div>
  );
}
