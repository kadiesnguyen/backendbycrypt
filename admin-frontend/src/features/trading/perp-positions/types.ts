export type PerpPosition = {
  id: number;
  uid: number;
  username: string;
  symbol: string;
  side: string;
  qty: string;
  entry_price: string;
  leverage: number;
  margin: string;
  liq_price: string;
  unrealized_pnl: string;
  mark_price?: string | null;
  status: number;
  status_label: string;
  kongyk: number;
  kongyk_label: string;
  admin_notified: number;
  opened_at: string | null;
  closed_at: string | null;
  close_price: string | null;
  realized_pnl: string | null;
};

export type PerpFill = {
  id: number;
  uid: number;
  position_id: number;
  symbol: string;
  side: string;
  action: string;
  qty: string;
  price: string;
  leverage: number;
  margin_delta: string;
  fee: string;
  pnl: string;
  created_at: string | null;
};

export type ListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type PerpPositionsListResponse = {
  status: boolean;
  data: PerpPosition[];
  meta: ListMeta;
};

export type PerpFillsListResponse = {
  status: boolean;
  data: PerpFill[];
  meta: ListMeta;
};

export type PerpPositionsListParams = {
  page?: number;
  per_page?: number;
  username?: string;
  symbol?: string;
  scope?: "open" | "closed" | "all";
};

export type SetPerpWinLossPayload = {
  id: number;
  kongyk: 0 | 1 | 2;
};

export type ActionResponse = {
  status: boolean;
  message: string;
};

export type PerpSettingsResponse = {
  status: boolean;
  data: { perp_win_rate: number };
};
