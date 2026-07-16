"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchContractSettings } from "./api";

export function useContractSettings() {
  return useQuery({
    queryKey: ["admin", "contract-settings"],
    queryFn: fetchContractSettings,
  });
}
