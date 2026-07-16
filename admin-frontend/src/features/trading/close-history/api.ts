import { apiClient } from "@/lib/api-client";
import { toQuery } from "@/lib/to-query";
import type { OrderListParams, OrderListResponse } from "./types";
export function fetchOrders(params: OrderListParams = {}): Promise<OrderListResponse> {
  return apiClient<OrderListResponse>("/contract-orders/closed" + toQuery({ page: params.page, per_page: params.per_page ?? 15, username: params.username }));
}
