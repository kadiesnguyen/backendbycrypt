"use client";

import { useMemo, useState } from "react";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
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
import { ArticleFormDialog } from "./ArticleFormDialog";
import { ArticleListSkeleton } from "./ArticleListSkeleton";
import { useArticle, useArticleActions, useArticles } from "./useArticles";
import type { Article, ArticleUpsertPayload } from "./types";

export function ArticleListContainer() {
  const { t } = useI18n();
  const { page, updateParams } = useUrlParams();
  const [formOpen, setFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [pendingDelete, setPendingDelete] = useState<Article | null>(null);
  const [formError, setFormError] = useState<string | null>(null);
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15 }), [page]);
  const { data, isLoading, isError, error, refetch, isFetching } = useArticles(queryParams);
  const { data: detail } = useArticle(formOpen ? editingId : null);
  const { create, update, remove } = useArticleActions();
  const items = data?.data ?? [];
  const meta = data?.meta;
  const editing = editingId ? (detail?.data ?? null) : null;

  const columns = [
    { key: "title", label: "Title" },
    { key: "status_label", label: t("common.status") },
    { key: "actions", label: t("common.actions") },
  ];

  const handleSubmit = async (payload: ArticleUpsertPayload) => {
    setFormError(null);
    try {
      if (editingId) await update.mutateAsync({ id: editingId, payload });
      else await create.mutateAsync(payload);
      setFormOpen(false);
      setEditingId(null);
    } catch (err) {
      setFormError(err instanceof Error ? err.message : t("common.saveFailed"));
    }
  };

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey="page.articles.title"
        descriptionKey="page.articles.description"
        action={
          <button
            type="button"
            onClick={() => {
              setEditingId(null);
              setFormOpen(true);
            }}
            className="rounded bg-primary px-4 py-2 text-sm text-background"
          >
            {t("page.articles.create")}
          </button>
        }
      />
      {isLoading ? <ArticleListSkeleton /> : null}
      {isError ? (
        <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState
          titleKey="page.articles.noResults"
          descriptionKey="common.noResultsHint"
          action={
            <button type="button" onClick={() => setFormOpen(true)} className="rounded bg-primary px-4 py-2 text-sm text-background">
              {t("page.articles.create")}
            </button>
          }
        />
      ) : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="title">{item.title}</DataTableCell>
                <DataTableCell columnKey="status_label">{item.status_label}</DataTableCell>
                <DataTableCell columnKey="actions" actions className="space-x-2">
                  <button
                    type="button"
                    onClick={() => {
                      setEditingId(item.id);
                      setFormOpen(true);
                    }}
                    className="text-sm text-primary"
                  >
                    {t("common.edit")}
                  </button>
                  <button type="button" onClick={() => setPendingDelete(item)} className="text-sm text-danger">
                    {t("common.delete")}
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
      <ArticleFormDialog
        isOpen={formOpen}
        editing={editing}
        isPending={create.isPending || update.isPending}
        error={formError}
        onSubmit={handleSubmit}
        onClose={() => {
          if (!create.isPending && !update.isPending) {
            setFormOpen(false);
            setEditingId(null);
          }
        }}
      />
      <ConfirmDialog
        isOpen={pendingDelete !== null}
        title={t("common.delete")}
        message={pendingDelete ? `"${pendingDelete.title}"?` : ""}
        confirmLabel={t("common.delete")}
        variant="danger"
        isPending={remove.isPending}
        onConfirm={async () => {
          if (pendingDelete) {
            await remove.mutateAsync([pendingDelete.id]);
            setPendingDelete(null);
          }
        }}
        onCancel={() => {
          if (!remove.isPending) setPendingDelete(null);
        }}
      />
    </div>
  );
}
