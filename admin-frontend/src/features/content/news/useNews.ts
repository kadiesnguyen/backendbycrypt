"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchNews, fetchNewsItem } from "./api";
import type { NewsListParams } from "./types";

export function useNews(params: NewsListParams) {
  return useQuery({
    queryKey: ["admin", "news", params],
    queryFn: () => fetchNews(params),
    placeholderData: (prev) => prev,
  });
}

export function useNewsItem(id: number | null) {
  return useQuery({
    queryKey: ["admin", "news", "detail", id],
    queryFn: () => fetchNewsItem(id!),
    enabled: id !== null && id > 0,
  });
}
