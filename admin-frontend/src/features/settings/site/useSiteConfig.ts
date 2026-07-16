"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchSiteConfig } from "./api";

export function useSiteConfig() {
  return useQuery({
    queryKey: ["admin", "site-config"],
    queryFn: fetchSiteConfig,
  });
}
