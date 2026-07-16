"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { approveWithdrawal, deleteWithdrawal, rejectWithdrawal } from "./api";

export function useWithdrawalActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "withdrawals"] });
    queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] });
  };

  const approve = useMutation({
    mutationFn: approveWithdrawal,
    onSuccess: invalidate,
  });

  const reject = useMutation({
    mutationFn: rejectWithdrawal,
    onSuccess: invalidate,
  });

  const remove = useMutation({
    mutationFn: deleteWithdrawal,
    onSuccess: invalidate,
  });

  return { approve, reject, remove };
}
