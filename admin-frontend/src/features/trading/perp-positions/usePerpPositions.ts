"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchPerpPositions } from "./api";
import { perpPositionsQueryKey } from "./query-keys";
import type { PerpPositionsListParams } from "./types";

export function usePerpPositions(params: PerpPositionsListParams) {
  return useQuery({
    queryKey: [...perpPositionsQueryKey, params],
    queryFn: () => fetchPerpPositions(params),
  });
}
