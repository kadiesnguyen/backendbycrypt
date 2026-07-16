"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { fetchSystemParams, updateSystemParams } from "./api";
export const systemParamsKey = ["admin", "system-params"] as const;
export function useSystemParams() { return useQuery({ queryKey: systemParamsKey, queryFn: fetchSystemParams }); }
export function useSystemParamsActions() { const qc = useQueryClient(); return useMutation({ mutationFn: updateSystemParams, onSuccess: () => qc.invalidateQueries({ queryKey: systemParamsKey }) }); }
