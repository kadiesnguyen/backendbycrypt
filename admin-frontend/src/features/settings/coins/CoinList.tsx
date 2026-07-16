import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import type { AdminCoin } from "./types";

type CoinListProps = {
  items: AdminCoin[];
  onEdit: (item: AdminCoin) => void;
  onToggleStatus: (item: AdminCoin) => void;
  onDelete: (item: AdminCoin) => void;
  pendingId: number | null;
};

function statusLabel(status: number): string {
  return status === 1 ? "Enabled" : "Disabled";
}

function statusClass(status: number): string {
  return status === 1 ? "bg-success/15 text-success" : "bg-surface-elevated text-muted";
}

function typeLabel(type: number): string {
  if (type === 1) return "Crypto";
  if (type === 2) return "Fiat";
  if (type === 3) return "Other";
  return String(type);
}

export function CoinList({ items, onEdit, onToggleStatus, onDelete, pendingId }: CoinListProps) {
  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              Name
            </th>
            <th scope="col" className={thClassName}>
              Title
            </th>
            <th scope="col" className={thClassName}>
              Type
            </th>
            <th scope="col" className={thClassName}>
              Sort
            </th>
            <th scope="col" className={thClassName}>
              Status
            </th>
            <th scope="col" className={thClassName}>
              Actions
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {items.map((item) => {
            const isBusy = pendingId === item.id;

            return (
              <tr key={item.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="px-4 py-3 font-medium text-foreground">{item.name}</td>
                <td className="px-4 py-3 text-foreground">{item.title ?? "—"}</td>
                <td className="px-4 py-3 text-muted">{typeLabel(item.type)}</td>
                <td className="px-4 py-3 text-muted">{item.sort}</td>
                <td className="px-4 py-3">
                  <span
                    className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${statusClass(item.status)}`}
                  >
                    {statusLabel(item.status)}
                  </span>
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
                      onClick={() => onToggleStatus(item)}
                      className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                    >
                      {item.status === 1 ? "Disable" : "Enable"}
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
