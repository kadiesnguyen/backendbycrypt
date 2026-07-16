"use client";

import { useMemo, useState } from "react";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { ActionButton, ToolbarActions, useRowSelection } from "@/components/actions";
import { DataTableCell, DataTable,
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
  RowCheckbox,
  SelectCell,
  UsernameFilter, } from "@/components/list/ListPageParts"
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { useI18n } from "@/lib/i18n/useI18n";
import { useUrlParams } from "@/hooks/useUrlParams";
import { deleteNotices, sendNotice } from "./api";
import { fetchUsers } from "../api";
import { NoticeListSkeleton } from "./NoticeListSkeleton";
import { useNotices } from "./useNotices";
import type { Notice } from "./types";

type NoticeListContainerProps = {
  titleKey?: string;
  descriptionKey?: string;
  emptyTitleKey?: string;
};

function formatDateTime(value: string): string {
  try {
    return new Date(value).toLocaleString("vi-VN", {
      hour: "2-digit",
      minute: "2-digit",
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });
  } catch {
    return value;
  }
}

export function NoticeListContainer({
  titleKey = "page.notices.title",
  descriptionKey = "page.notices.description",
  emptyTitleKey = "page.notices.noResults",
}: NoticeListContainerProps) {
  const { t } = useI18n();
  const { page, updateParams, getParam } = useUrlParams();
  const account = getParam("account") || getParam("username");
  const [accountInput, setAccountInput] = useState(account);
  const [formOpen, setFormOpen] = useState(false);
  const [viewNotice, setViewNotice] = useState<Notice | null>(null);
  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  const [targetAccount, setTargetAccount] = useState("");
  const [formError, setFormError] = useState<string | null>(null);
  const [pendingDelete, setPendingDelete] = useState(false);
  const queryClient = useQueryClient();
  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: 15, account: account || undefined }),
    [page, account],
  );
  const { data, isLoading, isError, error, refetch, isFetching } = useNotices(queryParams);
  const send = useMutation({
    mutationFn: sendNotice,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["admin", "notices"] }),
  });
  const remove = useMutation({
    mutationFn: deleteNotices,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["admin", "notices"] }),
  });
  const items = data?.data ?? [];
  const meta = data?.meta;
  const selection = useRowSelection(items);

  const columns = [
    { key: "account", label: t("common.username") },
    { key: "title", label: t("common.title") },
    { key: "content", label: t("common.content") },
    { key: "addtime", label: t("common.time") },
    { key: "user_view", label: t("page.notices.readStatus") },
  ];

  const renderReadStatus = (notice: Notice) => {
    const unread = Number(notice.user_view) === 1;
    return (
      <span className={unread ? "text-danger" : "text-success"}>
        {unread ? t("page.notices.unread") : t("page.notices.read")}
      </span>
    );
  };

  return (
    <div className="space-y-6">
      <PageHeader
        titleKey={titleKey}
        descriptionKey={descriptionKey}
        action={
          <ToolbarActions>
            <ActionButton
              variant="ghost"
              onClick={() => {
                setAccountInput("");
                updateParams({ account: null, page: "1" });
              }}
            >
              {t("action.resetSearch")}
            </ActionButton>
            <ActionButton
              variant="danger"
              disabled={selection.selectedIds.length === 0}
              onClick={() => setPendingDelete(true)}
            >
              {t("common.delete")}
            </ActionButton>
            <ActionButton variant="primary" onClick={() => setFormOpen(true)}>
              {t("page.notices.send")}
            </ActionButton>
          </ToolbarActions>
        }
      />

      <UsernameFilter
        value={accountInput}
        onChange={setAccountInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ account: accountInput.trim() || null, page: "1" });
        }}
      />

      {isLoading ? <NoticeListSkeleton /> : null}
      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("common.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}
      {!isLoading && !isError && items.length === 0 ? (
        <EmptyState titleKey={emptyTitleKey} descriptionKey="common.noResultsHint" />
      ) : null}

      {!isLoading && !isError && items.length > 0 ? (
        <>
          <PageMetaBar meta={meta} isFetching={isFetching} />
          <DataTable
            columns={columns}
            selectable
            allSelected={selection.allSelected}
            someSelected={selection.someSelected}
            onToggleAll={selection.toggleAll}
          >
            {items.map((item) => (
              <tr
                key={item.id}
                className="cursor-pointer hover:bg-surface-elevated/40"
                onClick={() => setViewNotice(item)}
              >
                <SelectCell onClick={(e) => e.stopPropagation()}>
                  <RowCheckbox
                    checked={selection.isSelected(item.id)}
                    onChange={() => selection.toggleOne(item.id)}
                  />
                </SelectCell>
                <DataTableCell columnKey="account" className="break-all font-medium">{item.account}</DataTableCell>
                <DataTableCell columnKey="title">{item.title}</DataTableCell>
                <DataTableCell columnKey="content" className="text-sm text-muted">{item.content}</DataTableCell>
                <DataTableCell columnKey="addtime" className="whitespace-nowrap text-sm">{formatDateTime(item.addtime)}</DataTableCell>
                <DataTableCell columnKey="user_view">{renderReadStatus(item)}</DataTableCell>
              </tr>
            ))}
          </DataTable>
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}

      {viewNotice ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <div className="w-full max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6">
            <h2 className="text-lg font-semibold">{viewNotice.title}</h2>
            <div className="space-y-2 text-sm">
              <p>
                <span className="text-muted">{t("common.username")}: </span>
                {viewNotice.account}
              </p>
              <p>
                <span className="text-muted">{t("common.time")}: </span>
                {formatDateTime(viewNotice.addtime)}
              </p>
              <p>
                <span className="text-muted">{t("page.notices.readStatus")}: </span>
                {renderReadStatus(viewNotice)}
              </p>
            </div>
            <p className="whitespace-pre-wrap text-sm leading-relaxed">{viewNotice.content}</p>
            <div className="flex justify-end">
              <ActionButton variant="ghost" type="button" onClick={() => setViewNotice(null)}>
                {t("common.cancel")}
              </ActionButton>
            </div>
          </div>
        </div>
      ) : null}

      {formOpen ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
          <form
            onSubmit={async (e) => {
              e.preventDefault();
              setFormError(null);
              try {
                const trimmedAccount = targetAccount.trim();
                if (trimmedAccount) {
                  const usersResponse = await fetchUsers({
                    username: trimmedAccount,
                    per_page: 1,
                    page: 1,
                  });
                  const user = usersResponse.data?.[0];
                  if (!user) {
                    throw new Error(t("page.notices.userNotFound"));
                  }
                  await send.mutateAsync({
                    title,
                    content,
                    type: 1,
                    user_id: user.id,
                  });
                } else {
                  await send.mutateAsync({ title, content, type: 2 });
                }
                setFormOpen(false);
                setTitle("");
                setContent("");
                setTargetAccount("");
              } catch (err) {
                setFormError(err instanceof Error ? err.message : t("common.actionFailed"));
              }
            }}
            className="w-full max-w-lg space-y-4 rounded-lg border border-border bg-surface p-6"
          >
            <h2 className="text-lg font-semibold">{t("page.notices.send")}</h2>
            {formError ? <p className="text-sm text-danger">{formError}</p> : null}
            <input
              placeholder={`${t("common.username")} (${t("page.notices.broadcastHint")})`}
              value={targetAccount}
              onChange={(e) => setTargetAccount(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <input
              placeholder={t("common.title")}
              required
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <textarea
              placeholder={t("common.content")}
              required
              rows={4}
              value={content}
              onChange={(e) => setContent(e.target.value)}
              className="w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm"
            />
            <div className="flex justify-end gap-2">
              <ActionButton variant="ghost" type="button" onClick={() => setFormOpen(false)}>
                {t("common.cancel")}
              </ActionButton>
              <ActionButton variant="primary" type="submit" disabled={send.isPending}>
                {t("common.confirm")}
              </ActionButton>
            </div>
          </form>
        </div>
      ) : null}

      <ConfirmDialog
        isOpen={pendingDelete}
        title={t("common.delete")}
        message={t("page.notices.deleteConfirm", { count: String(selection.selectedIds.length) })}
        confirmLabel={t("common.delete")}
        variant="danger"
        isPending={remove.isPending}
        onConfirm={async () => {
          await remove.mutateAsync(selection.selectedIds);
          selection.clearSelection();
          setPendingDelete(false);
        }}
        onCancel={() => {
          if (!remove.isPending) setPendingDelete(false);
        }}
      />
    </div>
  );
}
