export type AdminLoan = {
  id: number;
  user_id: number;
  username: string;
  amount: string;
  currency: string;
  duration_days: number;
  daily_interest_rate: string;
  lender_name: string;
  interest_amount: string;
  repay_amount: string;
  status: "pending" | "rejected" | "active" | "repaid" | "overdue" | string;
  status_label: string;
  note: string | null;
  img_front: string | null;
  img_back: string | null;
  approved_at: string | null;
  due_at: string | null;
  repaid_at: string | null;
  created_at: string | null;
  updated_at: string | null;
};

export type LoansListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type LoansListResponse = {
  status: boolean;
  data: AdminLoan[];
  meta: LoansListMeta;
};

export type LoansListParams = {
  page?: number;
  per_page?: number;
  username?: string;
  status?: string;
};

export type LoanActionResponse = {
  status: boolean;
  message: string;
  data?: AdminLoan;
};

export type LoanSettings = {
  enabled: boolean;
  min_amount: string;
  max_amount: string;
  duration_days: number;
  daily_interest_rate: string;
  lender_name: string;
  updated_at: string | null;
};

export type LoanSettingsResponse = {
  status: boolean;
  data: LoanSettings;
  message?: string;
};

export type LoanSettingsUpdatePayload = {
  enabled: boolean;
  min_amount: number;
  max_amount: number;
  duration_days: number;
  daily_interest_rate: number;
  lender_name: string;
};
