"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchLoginLogs } from "./api";
import type { LoginLogListParams } from "./types";

export const loginLogsQueryKey = ["admin", "login-logs"] as const;

export function useLoginLogs(params: LoginLogListParams) {
  return useQuery({
    queryKey: [...loginLogsQueryKey, params],
    queryFn: () => fetchLoginLogs(params),
  });
}
