import { apiClient, getAdminToken } from "@/lib/api-client";
import type {
  StakeDetailResponse,
  StakeFormMetaResponse,
  StakeMutationResponse,
  StakeUpsertPayload,
  StakesListParams,
  StakesListResponse,
  StakingLogsListParams,
  StakingLogsListResponse,
} from "./types";

function getBaseUrl(): string {
  return process.env.NEXT_PUBLIC_ADMIN_API_URL ?? "http://localhost:8000/api/admin";
}

function toQuery(params: Record<string, string | number | undefined>): string {
  const search = new URLSearchParams();
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== "") {
      search.set(key, String(value));
    }
  }
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchStakes(params: StakesListParams = {}): Promise<StakesListResponse> {
  return apiClient<StakesListResponse>(`/stakes${toQuery(params)}`);
}

export function fetchStakeFormMeta(): Promise<StakeFormMetaResponse> {
  return apiClient<StakeFormMetaResponse>("/stakes/form-meta");
}

export function fetchStake(id: number): Promise<StakeDetailResponse> {
  return apiClient<StakeDetailResponse>(`/stakes/${id}`);
}

export function createStake(payload: StakeUpsertPayload): Promise<StakeMutationResponse> {
  return apiClient<StakeMutationResponse>("/stakes", {
    method: "POST",
    body: payload,
  });
}

export function updateStake(
  id: number,
  payload: StakeUpsertPayload,
): Promise<StakeMutationResponse> {
  return apiClient<StakeMutationResponse>(`/stakes/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function fetchStakingLogs(
  params: StakingLogsListParams = {},
): Promise<StakingLogsListResponse> {
  return apiClient<StakingLogsListResponse>(`/staking-logs${toQuery(params)}`);
}

export async function uploadStakeImage(file: File): Promise<{ status: boolean; data: { path: string } }> {
  const form = new FormData();
  form.append("file", file);

  const token = getAdminToken();
  const headers: HeadersInit = {};
  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  const response = await fetch(`${getBaseUrl()}/stakes/upload-image`, {
    method: "POST",
    headers,
    body: form,
  });

  const payload = await response.json().catch(() => null);

  if (!response.ok) {
    const message =
      typeof payload === "object" && payload !== null && "message" in payload
        ? String((payload as { message: unknown }).message)
        : `Upload failed (${response.status})`;
    throw new Error(message);
  }

  return payload as { status: boolean; data: { path: string } };
}

export function stakeImageUrl(filename: string | null | undefined): string | null {
  if (!filename) return null;
  const api = getBaseUrl().replace(/\/api\/admin\/?$/, "");
  return `${api}/Upload/public/${filename}`;
}
