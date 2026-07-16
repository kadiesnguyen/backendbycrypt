"use client";

import { useQuery } from "@tanstack/react-query";
import { fetchDepositPort, fetchDepositPortFormMeta, fetchDepositPorts } from "./api";

export function useDepositPorts() {
  return useQuery({
    queryKey: ["admin", "deposit-ports"],
    queryFn: fetchDepositPorts,
  });
}

export function useDepositPort(id: number | null) {
  return useQuery({
    queryKey: ["admin", "deposit-ports", "detail", id],
    queryFn: () => fetchDepositPort(id!),
    enabled: id !== null && id > 0,
  });
}

export function useDepositPortFormMeta() {
  return useQuery({
    queryKey: ["admin", "deposit-ports", "form-meta"],
    queryFn: fetchDepositPortFormMeta,
  });
}
