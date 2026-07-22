import { TableShell, tableClassName, thClassName, theadClassName } from "@/components/list/TableShell";

export function PerpPositionListSkeleton() {
  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            {Array.from({ length: 10 }).map((_, i) => (
              <th key={i} scope="col" className={thClassName}>
                <div className="h-3 w-16 animate-pulse rounded bg-border/60" />
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {Array.from({ length: 5 }).map((_, row) => (
            <tr key={row} className="border-t border-border">
              {Array.from({ length: 10 }).map((_, col) => (
                <td key={col} className="px-3 py-3">
                  <div className="h-4 animate-pulse rounded bg-border/40" />
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </TableShell>
  );
}
