import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { formatTimestamp } from "@/features/finance/lib/format";
import type { AdminNews } from "./types";

type NewsListProps = {
  items: AdminNews[];
  onEdit: (item: AdminNews) => void;
  onDelete: (item: AdminNews) => void;
  pendingDeleteId: number | null;
};

function statusClass(status: number): string {
  return status === 1 ? "bg-success/15 text-success" : "bg-surface-elevated text-muted";
}

export function NewsList({ items, onEdit, onDelete, pendingDeleteId }: NewsListProps) {
  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              Title
            </th>
            <th scope="col" className={thClassName}>
              Status
            </th>
            <th scope="col" className={thClassName}>
              Updated
            </th>
            <th scope="col" className={thClassName}>
              Actions
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {items.map((item) => {
            const isBusy = pendingDeleteId === item.id;

            return (
              <tr key={item.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="px-4 py-3 font-medium text-foreground">{item.title}</td>
                <td className="px-4 py-3">
                  <span
                    className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${statusClass(item.status)}`}
                  >
                    {item.status_label}
                  </span>
                </td>
                <td className="px-4 py-3 text-muted">
                  {formatTimestamp(item.updated_at ?? item.created_at)}
                </td>
                <td className="px-4 py-3">
                  <div className="flex flex-wrap gap-2">
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onEdit(item)}
                      className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                    >
                      Edit
                    </button>
                    <button
                      type="button"
                      disabled={isBusy}
                      onClick={() => onDelete(item)}
                      className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
