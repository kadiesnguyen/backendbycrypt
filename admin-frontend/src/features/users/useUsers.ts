"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchUsers } from "./api";
import type { UsersListParams } from "./types";

export function useUsers(params: UsersListParams) {
  return useQuery({
    queryKey: ["admin", "users", params],
    queryFn: () => fetchUsers(params),
    placeholderData: (prev) => prev,
  });
}
