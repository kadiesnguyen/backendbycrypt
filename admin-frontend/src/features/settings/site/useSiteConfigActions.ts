"use client";

import { useMutation, useQueryClient } from "@tanstack/react-query";
import { updateSiteConfig } from "./api";
import type { SiteConfigUpdatePayload } from "./types";

export function useSiteConfigActions() {
  const queryClient = useQueryClient();

  const save = useMutation({
    mutationFn: (payload: SiteConfigUpdatePayload) => updateSiteConfig(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin", "site-config"] });
    },
  });

  return { save };
}
