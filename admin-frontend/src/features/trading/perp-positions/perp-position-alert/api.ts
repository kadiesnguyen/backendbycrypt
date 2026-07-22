import { apiClient } from "@/lib/api-client";
import type { MarkPerpNotifiedResponse, PerpPositionAlertResponse } from "./types";

export function fetchPerpPositionAlert(): Promise<PerpPositionAlertResponse> {
  return apiClient<PerpPositionAlertResponse>("/perp-positions/pending-count");
}

export function markPerpPositionsNotified(): Promise<MarkPerpNotifiedResponse> {
  return apiClient<MarkPerpNotifiedResponse>("/perp-positions/mark-notified", {
    method: "POST",
  });
}
