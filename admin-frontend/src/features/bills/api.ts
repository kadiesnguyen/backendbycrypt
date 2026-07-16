import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { BillListParams, BillListResponse } from "./types";

export function fetchBills(params: BillListParams = {}): Promise<BillListResponse> {
  return apiClient<BillListResponse>(
    `/bills${toQuery({
      page: params.page,
      per_page: params.per_page ?? 15,
      username: params.username,
      coinname: params.coinname,
      st: params.st,
    })}`,
  );
}

export function deleteBills(ids: number[]) {
  return apiClient("/bills", { method: "DELETE", body: { ids } });
}
