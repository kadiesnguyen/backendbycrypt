import type { Bill } from "@/features/bills/types";
import type { LoginLog } from "@/features/users/login-logs/types";

export type DashboardKpis = {
  total_members: number;
  online_members: number;
  total_agents: number;
  today_deposit: number;
  today_withdrawal: number;
  system_balance: number;
};

export type RevenueTrendPoint = {
  date: string;
  amount: number;
};

export type DepositWithdrawalPoint = {
  date: string;
  deposit: number;
  withdrawal: number;
};

export type DashboardAgent = {
  id: number;
  username: string;
  invit: string | null;
  referrals: number;
};

export type DashboardMember = {
  id: number;
  username: string;
  addtime: number;
  status: number;
  is_agent: number;
};

export type DashboardOverview = {
  kpis: DashboardKpis;
  revenue_trend: RevenueTrendPoint[];
  deposit_withdrawal_trend: DepositWithdrawalPoint[];
  top_agents: DashboardAgent[];
  latest_members: DashboardMember[];
  recent_logins: LoginLog[];
  recent_transactions: Bill[];
};

export type DashboardOverviewResponse = {
  status: boolean;
  data: DashboardOverview;
};
