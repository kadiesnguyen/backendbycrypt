import type { Bill } from "@/features/bills/types";
import {
  AnnotatedCell,
  TableShell,
  tableClassName,
} from "@/components/list/TableShell";
import { billStLabel } from "@/lib/i18n/entity-labels";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatDashboardTimestamp } from "../format";
import { DashboardCard } from "./DashboardCard";

const dashboardThClassName =
  "px-3 py-2.5 font-medium first:pl-4 last:pr-4 md:px-3 md:py-2.5 md:first:pl-4 md:last:pr-4";

type RecentTransactionsTableProps = {
  title: string;
  viewAllLabel: string;
  transactions: Bill[];
  emptyLabel: string;
  columns: {
    user: string;
    amount: string;
    coin: string;
    type: string;
    time: string;
  };
};

export function RecentTransactionsTable({
  title,
  viewAllLabel,
  transactions,
  emptyLabel,
  columns,
}: RecentTransactionsTableProps) {
  const { t: translate } = useI18n();

  return (
    <DashboardCard
      title={title}
      action={{ label: viewAllLabel, href: "/finance/bills" }}
      bodyClassName="min-h-[12.5rem] p-0"
      className="h-full"
    >
      {transactions.length === 0 ? (
        <p className="px-4 py-6 text-sm text-muted">{emptyLabel}</p>
      ) : (
        <TableShell className="rounded-none border-0">
          <table className={tableClassName}>
            <colgroup>
              <col className="w-[32%]" />
              <col className="w-[18%]" />
              <col className="hidden w-[14%] md:table-column" />
              <col className="w-[22%]" />
              <col className="hidden w-[14%] lg:table-column" />
            </colgroup>
            <thead className="dashboard-table-head border-b border-border">
              <tr>
                <th className={dashboardThClassName}>{columns.user}</th>
                <th className={dashboardThClassName}>{columns.amount}</th>
                <th className={`${dashboardThClassName} hidden md:table-cell`}>{columns.coin}</th>
                <th className={dashboardThClassName}>{columns.type}</th>
                <th className={`${dashboardThClassName} hidden lg:table-cell`}>{columns.time}</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border/40">
              {transactions.map((tx) => (
                <tr key={tx.id} className="transition hover:bg-surface-elevated/50">
                  <AnnotatedCell
                    label={columns.user}
                    className="min-w-0 font-medium text-foreground"
                  >
                    <span className="break-all">{tx.username}</span>
                  </AnnotatedCell>
                  <AnnotatedCell
                    label={columns.amount}
                    className="min-w-0 font-mono text-xs tabular-nums"
                  >
                    <span
                      className={
                        tx.st === 1 ? "text-success" : tx.st === 2 ? "text-danger" : "text-foreground"
                      }
                    >
                      {tx.st === 2 ? "−" : "+"}
                      {formatAmount(tx.num)}
                    </span>
                  </AnnotatedCell>
                  <AnnotatedCell
                    label={columns.coin}
                    className="hidden min-w-0 text-xs uppercase text-muted md:table-cell"
                  >
                    {tx.coinname}
                  </AnnotatedCell>
                  <AnnotatedCell label={columns.type} className="min-w-0">
                    <span
                      className={`inline-flex rounded-full px-2 py-0.5 text-xs font-medium ${
                        tx.st === 1
                          ? "bg-success/15 text-success"
                          : tx.st === 2
                            ? "bg-danger/15 text-danger"
                            : "bg-muted-chip text-muted"
                      }`}
                    >
                      {billStLabel(translate, tx.st)}
                    </span>
                  </AnnotatedCell>
                  <AnnotatedCell
                    label={columns.time}
                    className="hidden min-w-0 text-xs tabular-nums text-muted lg:table-cell"
                  >
                    {formatDashboardTimestamp(tx.addtime)}
                  </AnnotatedCell>
                </tr>
              ))}
            </tbody>
          </table>
        </TableShell>
      )}
    </DashboardCard>
  );
}
