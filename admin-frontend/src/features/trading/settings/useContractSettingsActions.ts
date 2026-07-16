"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { updateContractSettings } from "./api";

export function useContractSettingsActions() {
  const queryClient = useQueryClient();

  const save = useMutation({
    mutationFn: updateContractSettings,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin", "contract-settings"] });
    },
  });

  return { save };
}
