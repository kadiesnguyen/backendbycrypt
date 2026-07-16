import { apiClient } from "@/lib/api-client";
import type {
  NewsDeleteResponse,
  NewsDetailResponse,
  NewsListParams,
  NewsListResponse,
  NewsMutationResponse,
  NewsUpsertPayload,
} from "./types";

function toQuery(params: NewsListParams): string {
  const search = new URLSearchParams();
  if (params.page) search.set("page", String(params.page));
  if (params.per_page) search.set("per_page", String(params.per_page));
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchNews(params: NewsListParams = {}): Promise<NewsListResponse> {
  return apiClient<NewsListResponse>(`/news${toQuery(params)}`);
}

export function fetchNewsItem(id: number): Promise<NewsDetailResponse> {
  return apiClient<NewsDetailResponse>(`/news/${id}`);
}

export function createNews(payload: NewsUpsertPayload): Promise<NewsMutationResponse> {
  return apiClient<NewsMutationResponse>("/news", {
    method: "POST",
    body: payload,
  });
}

export function updateNews(
  id: number,
  payload: NewsUpsertPayload,
): Promise<NewsMutationResponse> {
  return apiClient<NewsMutationResponse>(`/news/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function deleteNews(ids: number[]): Promise<NewsDeleteResponse> {
  return apiClient<NewsDeleteResponse>("/news", {
    method: "DELETE",
    body: { ids },
  });
}
