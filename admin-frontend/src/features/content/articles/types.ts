import type { DetailResponse, ListParams, PaginatedResponse } from "@/lib/types/api";
export type Article = { id: number; title: string; img: string | null; content: string | null; status: number; status_label: string; addtime: string; };
export type ArticleListParams = ListParams & { status?: number };
export type ArticleListResponse = PaginatedResponse<Article>;
export type ArticleDetailResponse = DetailResponse<Article>;
export type ArticleUpsertPayload = { title: string; content?: string; img?: string; status?: number; };
