"use client";

import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import {
  addUserFunds,
  cancelUserAgent,
  createUser,
  fetchUser,
  reviewUserKyc,
  setUserAgent,
  updateUser,
  updateUsersStatus,
  updateUserTradeLock,
} from "./api";
import type { UserStatusType, UserUpsertPayload } from "./api";

export function useUser(id: number | null) {
  return useQuery({
    queryKey: ["admin", "users", "detail", id],
    queryFn: () => fetchUser(id!),
    enabled: id !== null,
  });
}

export function useUserActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "users"] });
    queryClient.invalidateQueries({ queryKey: ["admin", "agents"] });
  };

  const create = useMutation({
    mutationFn: (payload: UserUpsertPayload) => createUser(payload),
    onSuccess: invalidate,
  });

  const update = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: UserUpsertPayload }) =>
      updateUser(id, payload),
    onSuccess: invalidate,
  });

  const updateStatus = useMutation({
    mutationFn: ({ ids, type }: { ids: number[]; type: UserStatusType }) =>
      updateUsersStatus(ids, type),
    onSuccess: invalidate,
  });

  const updateTradeLock = useMutation({
    mutationFn: ({
      id,
      locked,
      message,
    }: {
      id: number;
      locked: boolean;
      message?: string;
    }) => updateUserTradeLock(id, { locked, message }),
    onSuccess: invalidate,
  });

  const addFunds = useMutation({
    mutationFn: ({ id, amount }: { id: number; amount: string }) => addUserFunds(id, amount),
    onSuccess: () => {
      invalidate();
      queryClient.invalidateQueries({ queryKey: ["admin", "user-assets"] });
      queryClient.invalidateQueries({ queryKey: ["admin", "bills"] });
    },
  });

  const kycReview = useMutation({
    mutationFn: ({
      id,
      payload,
    }: {
      id: number;
      payload: { rzstatus: 2 | 3; username: string; kjid?: number };
    }) => reviewUserKyc(id, payload),
    onSuccess: invalidate,
  });

  const setAgent = useMutation({
    mutationFn: (id: number) => setUserAgent(id),
    onSuccess: invalidate,
  });

  const cancelAgent = useMutation({
    mutationFn: (id: number) => cancelUserAgent(id),
    onSuccess: invalidate,
  });

  return { create, update, updateStatus, updateTradeLock, addFunds, kycReview, setAgent, cancelAgent };
}
