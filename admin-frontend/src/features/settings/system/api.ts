import { apiClient } from "@/lib/api-client";
import type { SystemParamsResponse } from "./types";
export function fetchSystemParams(): Promise<SystemParamsResponse> { return apiClient("/system-params"); }
export function updateSystemParams(payload: Partial<SystemParamsResponse["data"]>) { return apiClient("/system-params", { method: "PUT", body: payload }); }
