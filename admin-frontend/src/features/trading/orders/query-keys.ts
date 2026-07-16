import type { ContractOrdersListParams } from "./types";

export const contractOrdersQueryKey = ["admin", "contract-orders"] as const;

export function contractOrdersListKey(params: ContractOrdersListParams) {
  return [...contractOrdersQueryKey, params] as const;
}
