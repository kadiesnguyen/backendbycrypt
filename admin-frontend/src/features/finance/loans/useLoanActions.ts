"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { approveLoan, rejectLoan } from "./api";

export function useLoanActions() {
  const queryClient = useQueryClient();

  const invalidate = () => {
    queryClient.invalidateQueries({ queryKey: ["admin", "loans"] });
    queryClient.invalidateQueries({ queryKey: ["admin", "pending-counts"] });
  };

  const approve = useMutation({
    mutationFn: ({ id, note }: { id: number; note?: string }) => approveLoan(id, note),
    onSuccess: invalidate,
  });

  const reject = useMutation({
    mutationFn: ({ id, note }: { id: number; note?: string }) => rejectLoan(id, note),
    onSuccess: invalidate,
  });

  return { approve, reject };
}
