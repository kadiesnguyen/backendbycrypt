"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchLoans } from "./api";
import type { LoansListParams } from "./types";

export function useLoans(params: LoansListParams) {
  return useQuery({
    queryKey: ["admin", "loans", params],
    queryFn: () => fetchLoans(params),
    placeholderData: (prev) => prev,
  });
}
