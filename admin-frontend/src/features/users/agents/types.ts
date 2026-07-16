import type { ListParams, PaginatedResponse } from "@/lib/types/api";

export type Agent = {
  id: number;
  username: string;
  addip: string | null;
  addtime: number;
  invit: string | null;
  is_agent: number;
  one?: number;
  two?: number;
  three?: number;
  all?: number;
};

export type AgentListParams = ListParams;
export type AgentListResponse = PaginatedResponse<Agent>;
