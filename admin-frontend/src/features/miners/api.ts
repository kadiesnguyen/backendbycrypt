import { apiClient, getAdminToken } from "@/lib/api-client";
import type {
  MinerDetailResponse,
  MinerFormMetaResponse,
  MinerMutationResponse,
  MinerStatusResponse,
  MinerStatusType,
  MinerUpsertPayload,
  MinersListParams,
  MinersListResponse,
} from "./types";

function getBaseUrl(): string {
  return process.env.NEXT_PUBLIC_ADMIN_API_URL ?? "http://localhost:8000/api/admin";
}

function toQuery(params: MinersListParams): string {
  const search = new URLSearchParams();
  if (params.page) search.set("page", String(params.page));
  if (params.per_page) search.set("per_page", String(params.per_page));
  const qs = search.toString();
  return qs ? `?${qs}` : "";
}

export function fetchMiners(params: MinersListParams = {}): Promise<MinersListResponse> {
  return apiClient<MinersListResponse>(`/miners${toQuery(params)}`);
}

export function fetchMinerFormMeta(): Promise<MinerFormMetaResponse> {
  return apiClient<MinerFormMetaResponse>("/miners/form-meta");
}

export function fetchMiner(id: number): Promise<MinerDetailResponse> {
  return apiClient<MinerDetailResponse>(`/miners/${id}`);
}

export function createMiner(payload: MinerUpsertPayload): Promise<MinerMutationResponse> {
  return apiClient<MinerMutationResponse>("/miners", {
    method: "POST",
    body: payload,
  });
}

export function updateMiner(
  id: number,
  payload: MinerUpsertPayload,
): Promise<MinerMutationResponse> {
  return apiClient<MinerMutationResponse>(`/miners/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function updateMinerStatus(
  ids: number[],
  type: MinerStatusType,
): Promise<MinerStatusResponse> {
  return apiClient<MinerStatusResponse>("/miners/status", {
    method: "PUT",
    body: { ids, type },
  });
}

export async function uploadMinerImage(file: File): Promise<{ status: boolean; data: { path: string } }> {
  const form = new FormData();
  form.append("file", file);

  const token = getAdminToken();
  const headers: HeadersInit = {};
  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  const response = await fetch(`${getBaseUrl()}/miners/upload-image`, {
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

export function minerImageUrl(filename: string | null | undefined): string | null {
  if (!filename) return null;
  const api = getBaseUrl().replace(/\/api\/admin\/?$/, "");
  return `${api}/Upload/public/${filename}`;
}
