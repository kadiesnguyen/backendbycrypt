import { apiClient } from "@/lib/api-client";
import type {
  SiteConfigMutationResponse,
  SiteConfigResponse,
  SiteConfigUpdatePayload,
} from "./types";

export function fetchSiteConfig(): Promise<SiteConfigResponse> {
  return apiClient<SiteConfigResponse>("/site-config");
}

export function updateSiteConfig(
  payload: SiteConfigUpdatePayload,
): Promise<SiteConfigMutationResponse> {
  return apiClient<SiteConfigMutationResponse>("/site-config", {
    method: "PUT",
    body: payload,
  });
}
