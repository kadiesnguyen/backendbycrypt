import { TableSkeleton } from "@/components/list/ListPageParts";

export function BillListSkeleton() {
  return <TableSkeleton rows={8} cols={7} />;
}
