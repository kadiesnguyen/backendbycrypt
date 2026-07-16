"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { deleteBills } from "./api";

export function useBillActions() {
  const queryClient = useQueryClient();

  const remove = useMutation({
    mutationFn: (ids: number[]) => deleteBills(ids),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin", "bills"] });
    },
  });

  return { remove };
}
