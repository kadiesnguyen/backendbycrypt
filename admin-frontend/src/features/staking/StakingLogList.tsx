import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { formatTimestamp } from "@/features/finance/lib/format";
import { formatAmount } from "@/lib/format-number";
import type { AdminStakingLog } from "./types";

type StakingLogListProps = {
  logs: AdminStakingLog[];
};

function statusClass(status: number): string {
  return status === 1 ? "bg-primary/15 text-primary" : "bg-success/15 text-success";
}

export function StakingLogList({ logs }: StakingLogListProps) {
  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              Account
            </th>
            <th scope="col" className={thClassName}>
              Package
            </th>
            <th scope="col" className={thClassName}>
              Amount
            </th>
            <th scope="col" className={thClassName}>
              Days
            </th>
            <th scope="col" className={thClassName}>
              Rate
            </th>
            <th scope="col" className={thClassName}>
              Status
            </th>
            <th scope="col" className={thClassName}>
              Started
            </th>
            <th scope="col" className={thClassName}>
              Ends
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {logs.map((log) => (
            <tr key={log.id} className="bg-surface transition hover:bg-surface-elevated">
              <td className="break-all px-4 py-3 font-medium text-foreground">{log.account}</td>
              <td className="px-4 py-3 text-foreground">{log.name}</td>
              <td className="px-4 py-3 tabular-nums text-foreground">{formatAmount(log.num)}</td>
              <td className="px-4 py-3 tabular-nums text-muted">{formatAmount(log.open)}</td>
              <td className="px-4 py-3 text-foreground">{log.percent}%</td>
              <td className="px-4 py-3">
                <span
                  className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${statusClass(log.status)}`}
                >
                  {log.status_label}
                </span>
              </td>
              <td className="px-4 py-3 text-muted">{formatTimestamp(log.addtime)}</td>
              <td className="px-4 py-3 text-muted">{formatTimestamp(log.endtime)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </TableShell>
  );
}
