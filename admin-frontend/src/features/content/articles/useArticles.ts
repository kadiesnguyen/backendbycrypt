"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createArticle, deleteArticles, fetchArticle, fetchArticles, updateArticle } from "./api";
import type { ArticleListParams, ArticleUpsertPayload } from "./types";
export const articlesKey = ["admin", "articles"] as const;
export function useArticles(params: ArticleListParams) { return useQuery({ queryKey: [...articlesKey, params], queryFn: () => fetchArticles(params) }); }
export function useArticle(id: number | null) { return useQuery({ queryKey: [...articlesKey, id], queryFn: () => fetchArticle(id!), enabled: id !== null }); }
export function useArticleActions() {
  const qc = useQueryClient();
  return {
    create: useMutation({ mutationFn: createArticle, onSuccess: () => qc.invalidateQueries({ queryKey: articlesKey }) }),
    update: useMutation({ mutationFn: ({ id, payload }: { id: number; payload: ArticleUpsertPayload }) => updateArticle(id, payload), onSuccess: () => qc.invalidateQueries({ queryKey: articlesKey }) }),
    remove: useMutation({ mutationFn: deleteArticles, onSuccess: () => qc.invalidateQueries({ queryKey: articlesKey }) }),
  };
}
