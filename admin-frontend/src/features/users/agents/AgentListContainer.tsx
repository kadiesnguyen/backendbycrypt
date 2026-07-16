"use client";
import Link from "next/link";
import { useMemo, useState } from "react";
import { ActionButton, RowActions } from "@/components/actions";
import { DataTableCell, ActionsCell, DataTable, EmptyState, PageHeader, PageMetaBar, PaginationNav, UsernameFilter, actionsColumn } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { useUserActions } from "@/features/users/useUserActions";
import { AgentListSkeleton } from "./AgentListSkeleton";
import { useAgents } from "./useAgents";

export function AgentListContainer() {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const username = getParam("username");
  const [usernameInput, setUsernameInput] = useState(username);
  const [pendingCancelId, setPendingCancelId] = useState<number | null>(null);
  const { cancelAgent } = useUserActions();
  const queryParams = useMemo(() => ({ page: page > 0 ? page : 1, per_page: 15, username: username || undefined }), [page, username]);
  const { data, isLoading, isError, error, refetch, isFetching } = useAgents(queryParams);
  const items = data?.data ?? [];
  const meta = data?.meta;
  const columns = [
    { key: "username", label: t("common.username") },
    { key: "invit", label: t("common.invite") },
    { key: "one", label: t("common.level1") },
    { key: "two", label: t("common.level2") },
    { key: "all", label: t("common.total") },
    actionsColumn(t),
  ];

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.agents.title" descriptionKey="page.agents.description" />
      <UsernameFilter value={usernameInput} onChange={setUsernameInput} onSubmit={(e) => { e.preventDefault(); updateParams({ username: usernameInput.trim() || null, page: "1" }); }} />
      {isLoading ? <AgentListSkeleton /> : null}
      {isError ? <ErrorState message={error instanceof Error ? error.message : t("common.loadFailed")} retry={() => refetch()} /> : null}
      {!isLoading && !isError && items.length === 0 ? <EmptyState titleKey="page.agents.noResults" descriptionKey="common.noResultsHint" /> : null}
      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable columns={columns}>
            {items.map((item) => (
              <tr key={item.id}>
                <DataTableCell columnKey="username" className="break-all">{item.username}</DataTableCell>
                <DataTableCell columnKey="invit">{item.invit ?? "—"}</DataTableCell>
                <DataTableCell columnKey="one">{item.one ?? 0}</DataTableCell>
                <DataTableCell columnKey="two">{item.two ?? 0}</DataTableCell>
                <DataTableCell columnKey="all">{item.all ?? 0}</DataTableCell>
                <ActionsCell>
                  <RowActions>
                    <Link href={`/users?username=${encodeURIComponent(item.username)}`} className="rounded border border-border px-2.5 py-1 text-xs font-medium text-primary hover:bg-surface-elevated">{t("common.edit")}</Link>
                    <ActionButton variant="danger" onClick={() => setPendingCancelId(item.id)}>{t("action.cancelAgent")}</ActionButton>
                  </RowActions>
                </ActionsCell>
              </tr>
            ))}
          </DataTable>
          {meta ? <PaginationNav meta={meta} onPageChange={(p) => updateParams({ page: String(p) })} isFetching={isFetching} /> : null}
        </>
      ) : null}
      <ConfirmDialog isOpen={pendingCancelId !== null} title={t("action.cancelAgent")} message="Cancel agent status for this user?" confirmLabel={t("common.confirm")} variant="danger" isPending={cancelAgent.isPending} onConfirm={async () => { if (pendingCancelId) { await cancelAgent.mutateAsync(pendingCancelId); setPendingCancelId(null); } }} onCancel={() => { if (!cancelAgent.isPending) setPendingCancelId(null); }} />
    </div>
  );
}
