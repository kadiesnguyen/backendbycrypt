import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import type { AdminDepositPort } from "./types";

type DepositPortListProps = {
  items: AdminDepositPort[];
  onEdit: (item: AdminDepositPort) => void;
  onToggleStatus: (item: AdminDepositPort) => void;
  onDelete: (item: AdminDepositPort) => void;
  pendingId: number | null;
};

function statusLabel(status: number): string {
  return status === 1 ? "Enabled" : "Disabled";
}

function statusClass(status: number): string {
  return status === 1 ? "bg-success/15 text-success" : "bg-surface-elevated text-muted";
}

export function DepositPortList({
  items,
  onEdit,
  onToggleStatus,
  onDelete,
  pendingId,
}: DepositPortListProps) {
  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              Name
            </th>
            <th scope="col" className={thClassName}>
              Coin
            </th>
            <th scope="col" className={thClassName}>
              Wallet
            </th>
            <th scope="col" className={thClassName}>
              Address
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
                <td className="px-4 py-3 text-foreground">{item.coin ?? "—"}</td>
                <td className="px-4 py-3 text-muted">{item.wallet ?? "—"}</td>
                <td className="px-4 py-3 break-all text-muted">{item.address ?? "—"}</td>
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
