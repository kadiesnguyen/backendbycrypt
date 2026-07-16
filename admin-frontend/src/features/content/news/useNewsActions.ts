"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createNews, deleteNews, updateNews } from "./api";
import type { NewsUpsertPayload } from "./types";

export function useNewsActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "news"] });
  };

  const create = useMutation({
    mutationFn: (payload: NewsUpsertPayload) => createNews(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: NewsUpsertPayload }) =>
      updateNews(id, payload),
    onSuccess: invalidate,
  });

  const remove = useMutation({
    mutationFn: (ids: number[]) => deleteNews(ids),
    onSuccess: invalidate,
  });

  return { create, update, remove };
}
