"use client";

import { useEffect, useRef, useState } from "react";
import type { AdminNews, NewsUpsertPayload } from "./types";

type NewsFormDialogProps = {
  isOpen: boolean;
  editingItem: AdminNews | null;
  isLoadingDetail: boolean;
  isPending: boolean;
  error: string | null;
  onSubmit: (payload: NewsUpsertPayload) => void;
  onClose: () => void;
};

export function NewsFormDialog({
  isOpen,
  editingItem,
  isLoadingDetail,
  isPending,
  error,
  onSubmit,
  onClose,
}: NewsFormDialogProps) {
  const titleRef = useRef<HTMLInputElement>(null);
  const [title, setTitle] = useState("");
  const [coverImage, setCoverImage] = useState("");
  const [content, setContent] = useState("");
  const [status, setStatus] = useState("1");

  useEffect(() => {
    if (!isOpen) return;

    if (editingItem) {
      setTitle(editingItem.title);
      setCoverImage(editingItem.coverImage ?? "");
      setContent(editingItem.content ?? "");
      setStatus(String(editingItem.status));
    } else {
      setTitle("");
      setCoverImage("");
      setContent("");
      setStatus("1");
    }
  }, [isOpen, editingItem]);

  useEffect(() => {
    if (isOpen) {
      titleRef.current?.focus();
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
    onSubmit({
      title: title.trim(),
      coverImage: coverImage.trim() || undefined,
      content,
      status: Number(status),
    });
  };

  const isEdit = editingItem !== null;

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
        aria-labelledby="news-form-title"
        className="relative z-10 flex max-h-[90vh] w-full max-w-lg flex-col rounded-lg border border-border bg-surface shadow-lg"
      >
        <form onSubmit={handleSubmit} className="flex max-h-[90vh] flex-col">
          <div className="border-b border-border px-6 py-4">
            <h2 id="news-form-title" className="text-lg font-semibold text-foreground">
              {isEdit ? "Edit news" : "Create news"}
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
                Loading news details…
              </p>
            ) : (
              <>
                <div>
                  <label htmlFor="news-title" className="block text-sm font-medium text-foreground">
                    Title
                  </label>
                  <input
                    ref={titleRef}
                    id="news-title"
                    type="text"
                    required
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
                  />
                </div>
                <div>
                  <label htmlFor="news-cover" className="block text-sm font-medium text-foreground">
                    Cover image URL
                  </label>
                  <input
                    id="news-cover"
                    type="text"
                    value={coverImage}
                    onChange={(e) => setCoverImage(e.target.value)}
                    placeholder="Optional cover image path or URL"
                    className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground placeholder:text-muted"
                  />
                </div>
                <div>
                  <label htmlFor="news-content" className="block text-sm font-medium text-foreground">
                    Content
                  </label>
                  <textarea
                    id="news-content"
                    rows={6}
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                    className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
                  />
                </div>
                <div>
                  <label htmlFor="news-status" className="block text-sm font-medium text-foreground">
                    Status
                  </label>
                  <select
                    id="news-status"
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    className="mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground"
                  >
                    <option value="1">Display</option>
                    <option value="2">Hidden</option>
                  </select>
                </div>
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
              disabled={isPending || isLoadingDetail || !title.trim()}
              className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
            >
              {isPending ? "Saving…" : isEdit ? "Save changes" : "Create"}
            </button>
          </div>
        </form>
      </dialog>
    </div>
  );
}
