import { apiClient } from "@/lib/api-client";
import type {
  ActionResponse,
  PerpFillsListResponse,
  PerpPositionsListParams,
  PerpPositionsListResponse,
  PerpSettingsResponse,
  SetPerpWinLossPayload,
} from "./types";

function toQuery(params: Record<string, string | number | undefined>): string {
  const q = new URLSearchParams();
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== "") {
      q.set(key, String(value));
    }
  }
  const s = q.toString();
  return s ? `?${s}` : "";
}

export function fetchPerpPositions(
  params: PerpPositionsListParams,
): Promise<PerpPositionsListResponse> {
  return apiClient<PerpPositionsListResponse>(`/perp-positions${toQuery(params)}`);
}

export function fetchPerpFills(
  params: Pick<PerpPositionsListParams, "page" | "per_page" | "username" | "symbol">,
): Promise<PerpFillsListResponse> {
  return apiClient<PerpFillsListResponse>(`/perp-positions/fills${toQuery(params)}`);
}

export function setPerpWinLoss(payload: SetPerpWinLossPayload): Promise<ActionResponse> {
  return apiClient<ActionResponse>("/perp-positions/win-loss", {
    method: "PUT",
    body: payload,
  });
}

export function settlePerpPosition(id: number): Promise<ActionResponse> {
  return apiClient<ActionResponse>(`/perp-positions/${id}/settle`, { method: "POST" });
}

export function fetchPerpSettings(): Promise<PerpSettingsResponse> {
  return apiClient<PerpSettingsResponse>("/perp-settings");
}

export function updatePerpSettings(perp_win_rate: number): Promise<PerpSettingsResponse> {
  return apiClient<PerpSettingsResponse>("/perp-settings", {
    method: "PUT",
    body: { perp_win_rate },
  });
}
