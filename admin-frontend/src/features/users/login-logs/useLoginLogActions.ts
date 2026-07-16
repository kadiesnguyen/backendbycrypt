"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import {
  fetchLoginLog,
  updateLoginLog,
  updateLoginLogStatus,
} from "./api";
import type { LoginLogStatusType, LoginLogUpdatePayload } from "./api";

export function useLoginLog(id: number | null) {
  return useQuery({
    queryKey: ["admin", "user-login-logs", "detail", id],
    queryFn: () => fetchLoginLog(id!),
    enabled: id !== null,
  });
}

export function useLoginLogActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "user-login-logs"] });
  };

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: LoginLogUpdatePayload }) =>
      updateLoginLog(id, payload),
    onSuccess: invalidate,
  });

  const updateStatus = useMutation({
    mutationFn: ({ ids, type }: { ids: number[]; type: LoginLogStatusType }) =>
      updateLoginLogStatus(ids, type),
    onSuccess: invalidate,
  });

  return { update, updateStatus };
}
