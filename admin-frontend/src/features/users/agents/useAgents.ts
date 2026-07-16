"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchAgents } from "./api";
import type { AgentListParams } from "./types";

export const agentsQueryKey = ["admin", "agents"] as const;

export function useAgents(params: AgentListParams) {
  return useQuery({
    queryKey: [...agentsQueryKey, params],
    queryFn: () => fetchAgents(params),
  });
}
