"use client";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createAdmin, fetchAdmin, fetchAdmins, updateAdmin, updateAdminStatus } from "./api";
import type { AdminListParams, AdminUpsertPayload } from "./types";
export const adminsQueryKey = ["admin", "admins"] as const;
export function useAdmins(params: AdminListParams) { return useQuery({ queryKey: [...adminsQueryKey, params], queryFn: () => fetchAdmins(params) }); }
export function useAdmin(id: number | null) { return useQuery({ queryKey: [...adminsQueryKey, "detail", id], queryFn: () => fetchAdmin(id!), enabled: id !== null }); }
export function useAdminActions() {
  const qc = useQueryClient();
  return {
    create: useMutation({ mutationFn: createAdmin, onSuccess: () => qc.invalidateQueries({ queryKey: adminsQueryKey }) }),
    update: useMutation({ mutationFn: ({ id, payload }: { id: number; payload: AdminUpsertPayload }) => updateAdmin(id, payload), onSuccess: () => qc.invalidateQueries({ queryKey: adminsQueryKey }) }),
    updateStatus: useMutation({ mutationFn: ({ ids, type }: { ids: number[]; type: "forbid" | "resume" | "delete" }) => updateAdminStatus(ids, type), onSuccess: () => qc.invalidateQueries({ queryKey: adminsQueryKey }) }),
  };
}
