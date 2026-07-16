"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { fetchNotices, sendNotice } from "./api";
import type { NoticeListParams } from "./types";

export const noticesQueryKey = ["admin", "notices"] as const;

export function useNotices(params: NoticeListParams) {
  return useQuery({
    queryKey: [...noticesQueryKey, params],
    queryFn: () => fetchNotices(params),
  });
}

export function useNoticeActions() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: sendNotice,
    onSuccess: () => qc.invalidateQueries({ queryKey: noticesQueryKey }),
  });
}
