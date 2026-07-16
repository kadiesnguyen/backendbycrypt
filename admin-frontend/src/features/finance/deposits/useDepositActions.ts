"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { approveDeposit, deleteDeposit, rejectDeposit } from "./api";

export function useDepositActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "deposits"] });
    queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] });
  };

  const approve = useMutation({
    mutationFn: approveDeposit,
    onSuccess: invalidate,
  });

  const reject = useMutation({
    mutationFn: rejectDeposit,
    onSuccess: invalidate,
  });

  const remove = useMutation({
    mutationFn: deleteDeposit,
    onSuccess: invalidate,
  });

  return { approve, reject, remove };
}
