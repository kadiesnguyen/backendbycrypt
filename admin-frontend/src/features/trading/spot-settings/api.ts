import { apiClient } from "@/lib/api-client";
import type { SpotSettingsResponse } from "./types";
export function fetchSpotSettings(): Promise<SpotSettingsResponse> { return apiClient<SpotSettingsResponse>("/spot-settings"); }
export function updateSpotSettings(payload: { bb_kstime: string }): Promise<{ status: boolean; message: string }> {
  return apiClient("/spot-settings", { method: "PUT", body: payload });
}
