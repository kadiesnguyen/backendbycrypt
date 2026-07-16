import { apiClient } from "@/lib/api-client";
import type {
  QueueAction,
  QueueActionResponse,
  QueueListResponse,
  UpdateQueueEntryPayload,
  UpdateQueueEntryResponse,
} from "./types";

export function fetchContractQueue(): Promise<QueueListResponse> {
  return apiClient<QueueListResponse>("/contract-queue");
}

export function contractQueueAction(action: QueueAction): Promise<QueueActionResponse> {
  return apiClient<QueueActionResponse>("/contract-queue/action", {
    method: "POST",
    body: { action },
  });
}

export function updateContractQueueEntry(
  id: number,
  payload: UpdateQueueEntryPayload,
): Promise<UpdateQueueEntryResponse> {
  return apiClient<UpdateQueueEntryResponse>(`/contract-queue/${id}`, {
    method: "PUT",
    body: payload,
  });
}

export function deleteContractQueueEntry(id: number): Promise<QueueActionResponse> {
  return apiClient<QueueActionResponse>(`/contract-queue/${id}`, {
    method: "DELETE",
  });
}
