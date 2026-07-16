import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { ArticleDetailResponse, ArticleListParams, ArticleListResponse, ArticleUpsertPayload } from "./types";
export function fetchArticles(params: ArticleListParams = {}): Promise<ArticleListResponse> { return apiClient(`/articles${toQuery({ page: params.page, per_page: params.per_page ?? 15, status: params.status })}`); }
export function fetchArticle(id: number): Promise<ArticleDetailResponse> { return apiClient(`/articles/${id}`); }
export function createArticle(payload: ArticleUpsertPayload) { return apiClient("/articles", { method: "POST", body: payload }); }
export function updateArticle(id: number, payload: ArticleUpsertPayload) { return apiClient(`/articles/${id}`, { method: "PUT", body: payload }); }
export function deleteArticles(ids: number[]) { return apiClient("/articles", { method: "DELETE", body: { ids } }); }
