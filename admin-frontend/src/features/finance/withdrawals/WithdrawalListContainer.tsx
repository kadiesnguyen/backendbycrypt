"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
  UsernameFilter,
} from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { formatAmount } from "@/lib/format-number";
import { useI18n } from "@/lib/i18n/useI18n";
import { useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { WithdrawalList } from "./WithdrawalList";
import { WithdrawalListSkeleton } from "./WithdrawalListSkeleton";
import { useWithdrawalActions } from "./useWithdrawalActions";
import { useWithdrawals } from "./useWithdrawals";
import type { AdminWithdrawal } from "./types";

type PendingAction = {
  withdrawal: AdminWithdrawal;
  type: "approve" | "reject" | "delete";
};

export function WithdrawalListContainer() {
  const { t } = useI18n();
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();

  const page = Number(searchParams.get("page") ?? "1");
  const username = searchParams.get("username") ?? "";

  const [usernameInput, setUsernameInput] = useState(username);
  const [pendingAction, setPendingAction] = useState<PendingAction | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);

  const queryParams = useMemo(
    () => ({
      page: page > 0 ? page : 1,
      per_page: 15,
      username: username || undefined,
    }),
    [page, username],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = useWithdrawals(queryParams);
  const { approve, reject, remove } = useWithdrawalActions();

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") {
          next.delete(key);
        } else {
          next.set(key, value);
        }
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  const handleConfirm = async () => {
    if (!pendingAction) return;

    setActionError(null);
    const { withdrawal, type } = pendingAction;

    try {
      if (type === "approve") {
        await approve.mutateAsync(withdrawal.id);
      } else if (type === "reject") {
        await reject.mutateAsync(withdrawal.id);
      } else {
        await remove.mutateAsync(withdrawal.id);
      }
      setPendingAction(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const withdrawals = data?.data ?? [];
  const meta = data?.meta;
  const isActionPending = approve.isPending || reject.isPending || remove.isPending;
  const pendingActionId =
    isActionPending && pendingAction ? pendingAction.withdrawal.id : null;

  const confirmTitle = pendingAction?.type === "approve"
    ? t("page.withdrawals.confirm.approveTitle")
    : pendingAction?.type === "reject"
      ? t("page.withdrawals.confirm.rejectTitle")
      : t("page.withdrawals.confirm.deleteTitle");

  const confirmMessage = pendingAction
    ? pendingAction.type === "approve"
      ? t("page.withdrawals.confirm.approveMessage", {
          id: String(pendingAction.withdrawal.id),
          username: pendingAction.withdrawal.username,
          amount: formatAmount(pendingAction.withdrawal.mum),
          coin: pendingAction.withdrawal.coinname.toUpperCase(),
        })
      : pendingAction.type === "reject"
        ? t("page.withdrawals.confirm.rejectMessage", {
            id: String(pendingAction.withdrawal.id),
            username: pendingAction.withdrawal.username,
          })
        : t("page.withdrawals.confirm.deleteMessage", {
            id: String(pendingAction.withdrawal.id),
          })
    : "";

  const confirmLabel = pendingAction?.type === "approve"
    ? t("action.approve")
    : pendingAction?.type === "reject"
      ? t("action.reject")
      : t("common.delete");

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.withdrawals.title" descriptionKey="page.withdrawals.description" />

      <UsernameFilter
        value={usernameInput}
        onChange={setUsernameInput}
        onSubmit={(e) => {
          e.preventDefault();
          updateParams({ username: usernameInput.trim() || null, page: "1" });
        }}
      />

      {actionError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {actionError}
        </div>
      ) : null}

      {isLoading ? <WithdrawalListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.withdrawals.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && withdrawals.length === 0 ? (
        <EmptyState titleKey="page.withdrawals.noResults" descriptionKey="common.noResultsHint" />
      ) : null}

      {!isLoading && !isError && withdrawals.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <WithdrawalList
            withdrawals={withdrawals}
            pendingActionId={pendingActionId}
            onApprove={(withdrawal) => {
              setActionError(null);
              setPendingAction({ withdrawal, type: "approve" });
            }}
            onReject={(withdrawal) => {
              setActionError(null);
              setPendingAction({ withdrawal, type: "reject" });
            }}
            onDelete={(withdrawal) => {
              setActionError(null);
              setPendingAction({ withdrawal, type: "delete" });
            }}
          />
          {meta ? (
            <PaginationNav
              meta={meta}
              onPageChange={(p) => updateParams({ page: String(p) })}
              isFetching={isFetching}
            />
          ) : null}
        </>
      ) : null}

      <ConfirmDialog
        isOpen={pendingAction !== null}
        title={confirmTitle}
        message={confirmMessage}
        confirmLabel={confirmLabel}
        variant={pendingAction?.type === "reject" || pendingAction?.type === "delete" ? "danger" : "default"}
        isPending={isActionPending}
        onConfirm={handleConfirm}
        onCancel={() => {
          if (!isActionPending) {
            setPendingAction(null);
          }
        }}
      />
    </div>
  );
}
