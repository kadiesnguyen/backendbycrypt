import type { LoginLog } from "@/features/users/login-logs/types";
import {
  AnnotatedCell,
  TableShell,
  tableClassName,
} from "@/components/list/TableShell";
import { loginLogStatusLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { formatDashboardTimestamp } from "../format";
import { DashboardCard } from "./DashboardCard";

const dashboardThClassName =
  "px-3 py-2.5 font-medium first:pl-4 last:pr-4 md:px-3 md:py-2.5 md:first:pl-4 md:last:pr-4";

type RecentLoginTableProps = {
  title: string;
  viewAllLabel: string;
  logins: LoginLog[];
  emptyLabel: string;
  columns: {
    user: string;
    ip: string;
    location: string;
    time: string;
    status: string;
  };
};

export function RecentLoginTable({
  title,
  viewAllLabel,
  logins,
  emptyLabel,
  columns,
}: RecentLoginTableProps) {
  const { t: translate } = useI18n();

  return (
    <DashboardCard
      title={title}
      action={{ label: viewAllLabel, href: "/users/login-logs" }}
      bodyClassName="min-h-[12.5rem] p-0"
      className="h-full"
    >
      {logins.length === 0 ? (
        <p className="px-4 py-6 text-sm text-muted">{emptyLabel}</p>
      ) : (
        <TableShell className="rounded-none border-0">
          <table className={tableClassName}>
            <colgroup>
              <col className="w-[36%]" />
              <col className="w-[22%]" />
              <col className="hidden w-[24%] md:table-column" />
              <col className="w-[18%]" />
            </colgroup>
            <thead className="dashboard-table-head border-b border-border">
              <tr>
                <th className={dashboardThClassName}>{columns.user}</th>
                <th className={dashboardThClassName}>{columns.ip}</th>
                <th className={`${dashboardThClassName} hidden md:table-cell`}>{columns.time}</th>
                <th className={dashboardThClassName}>{columns.status}</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border/40">
              {logins.map((log) => (
                <tr key={log.id} className="transition hover:bg-surface-elevated/50">
                  <AnnotatedCell
                    label={columns.user}
                    className="min-w-0 font-medium text-foreground"
                  >
                    <span className="break-all">{log.username ?? "—"}</span>
                  </AnnotatedCell>
                  <AnnotatedCell
                    label={columns.ip}
                    className="min-w-0 font-mono text-xs text-muted"
                  >
                    {log.addip ?? "—"}
                  </AnnotatedCell>
                  <AnnotatedCell
                    label={columns.time}
                    className="hidden min-w-0 text-xs tabular-nums text-muted md:table-cell"
                  >
                    {formatDashboardTimestamp(log.addtime)}
                  </AnnotatedCell>
                  <AnnotatedCell label={columns.status} className="min-w-0">
                    <span
                      className={`inline-flex rounded-full px-2 py-0.5 text-xs font-medium ${
                        log.status === 1
                          ? "bg-success/15 text-success"
                          : log.status === 0
                            ? "bg-danger/15 text-danger"
                            : "bg-muted-chip text-muted"
                      }`}
                    >
                      {loginLogStatusLabel(translate, log.status)}
                    </span>
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
