import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { NavListParams, NavListResponse, NavPayload } from "./types";
export function fetchNavItems(params: NavListParams = {}): Promise<NavListResponse> { return apiClient(`/frontend-navigation${toQuery({ page: params.page, per_page: params.per_page ?? 15 })}`); }
export function createNavItem(payload: NavPayload) { return apiClient("/frontend-navigation", { method: "POST", body: payload }); }
export function updateNavItem(id: number, payload: NavPayload) { return apiClient(`/frontend-navigation/${id}`, { method: "PUT", body: payload }); }
