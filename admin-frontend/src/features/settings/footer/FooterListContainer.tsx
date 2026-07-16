"use client";

import { useMemo, useState } from "react";
import { ErrorState } from "@/components/ui/ErrorState";
import {
  DataTableCell,
  DataTable,
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
} from "@/components/list/ListPageParts";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { FooterListSkeleton } from "./FooterListSkeleton";
import { useNavActions, useNavItems } from "./useNav";
import type { NavItem, NavPayload } from "./types";

export function FooterListContainer() {
  const { t } = useI18n();
  const { page, updateParams } = useUrlParams();
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<NavItem | null>(null);
  const [title, setTitle] = useState("");
  const [url, setUrl] = useState("");
  const [name, setName] = useState("");
  const [formError, setFormError] = useState<string | null>(null);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15 }), [page]);
  const { data, isLoading, isError, error, refetch, isFetching } = useNavItems(queryParams);
  const { create, update } = useNavActions();
  const items = data?.data ?? [];
  const meta = data?.meta;

  const columns = [
    { key: "title", label: "Title" },
    { key: "url", label: "URL" },
    { key: "sort", label: "Sort" },
    { key: "actions", label: t("common.actions") },
  ];

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);
    const payload: NavPayload = { name, title, url, lang: "vi", status: 1 };
    try {
      if (editing) await update.mutateAsync({ id: editing.id, payload });
      else await create.mutateAsync(payload);
      setFormOpen(false);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
    }
  };

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey="page.footer.title"
        descriptionKey="page.footer.description"
        action={
          <button
            type="button"
            onClick={() => {
              setEditing(null);
              setTitle("");
              setUrl("");
              setName("");
              setFormOpen(true);
            }}
            className="rounded bg-primary px-4 py-2 text-sm text-background"
          >
            {t("page.footer.create")}
          </button>
        }
      />
      {isLoading ? <FooterListSkeleton /> : null}
      {isError ? (
        <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState titleKey="page.footer.noResults" descriptionKey="common.noResultsHint" />
      ) : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="title">{item.title}</DataTableCell>
                <DataTableCell columnKey="url" className="break-all">
                  {item.url}
                </DataTableCell>
                <DataTableCell columnKey="sort">{item.sort}</DataTableCell>
                <DataTableCell columnKey="actions" actions>
                  <button
                    type="button"
                    onClick={() => {
                      setEditing(item);
                      setTitle(item.title);
                      setUrl(item.url);
                      setName(item.name);
                      setFormOpen(true);
                    }}
                    className="text-sm text-primary"
                  >
                    {t("common.edit")}
                  </button>
                </DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? (
            <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} />
          ) : null}
        </>
      ) : null}
      {formOpen ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form onSubmit={handleSave} className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{editing ? t("common.edit") : t("page.footer.create")}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            <input
              placeholder="Name"
              required
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <input
              placeholder="Title"
              required
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <input
              placeholder="URL"
              required
              value={url}
              onChange={(e) => setUrl(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <div className="flex justify-end gap-2">
              <button type="button" onClick={() => setFormOpen(false)} className="rounded border border-border px-4 py-2 text-sm">
                {t("common.cancel")}
              </button>
              <button type="submit" className="rounded bg-primary px-4 py-2 text-sm text-background">
                {t("common.save")}
              </button>
            </div>
          </form>
        </div>
      ) : null}
    </div>
  );
}
