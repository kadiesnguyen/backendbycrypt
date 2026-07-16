"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createNavItem, fetchNavItems, updateNavItem } from "./api";
import type { NavListParams, NavPayload } from "./types";
export const footerKey = ["admin", "footer"] as const;
export function useNavItems(params: NavListParams) { return useQuery({ queryKey: [...footerKey, params], queryFn: () => fetchNavItems(params) }); }
export function useNavActions() { const qc = useQueryClient(); return { create: useMutation({ mutationFn: createNavItem, onSuccess: () => qc.invalidateQueries({ queryKey: footerKey }) }), update: useMutation({ mutationFn: ({ id, payload }: { id: number; payload: NavPayload }) => updateNavItem(id, payload), onSuccess: () => qc.invalidateQueries({ queryKey: footerKey }) }) }; }
