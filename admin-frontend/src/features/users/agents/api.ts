import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { AgentListParams, AgentListResponse } from "./types";

export function fetchAgents(params: AgentListParams = {}): Promise<AgentListResponse> {
  return apiClient<AgentListResponse>(
    `/agents${toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username })}`,
  );
}
