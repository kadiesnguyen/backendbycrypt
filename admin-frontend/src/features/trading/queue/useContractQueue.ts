"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchContractQueue } from "./api";

export function useContractQueue() {
  return useQuery({
    queryKey: ["admin", "contract-queue"],
    queryFn: fetchContractQueue,
    placeholderData: (prev) => prev,
  });
}
