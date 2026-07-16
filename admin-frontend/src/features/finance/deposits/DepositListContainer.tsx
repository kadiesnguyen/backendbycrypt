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
import { DepositList } from "./DepositList";
import { DepositListSkeleton } from "./DepositListSkeleton";
import { useDepositActions } from "./useDepositActions";
import { useDeposits } from "./useDeposits";
import type { AdminDeposit } from "./types";

type PendingAction = {
  deposit: AdminDeposit;
  type: "approve" | "reject" | "delete";
};

export function DepositListContainer() {
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

  const { data, isLoading, isError, error, refetch, isFetching } = useDeposits(queryParams);
  const { approve, reject, remove } = useDepositActions();

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
    const { deposit, type } = pendingAction;

    try {
      if (type === "approve") {
        await approve.mutateAsync(deposit.id);
      } else if (type === "reject") {
        await reject.mutateAsync(deposit.id);
      } else {
        await remove.mutateAsync(deposit.id);
      }
      setPendingAction(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const deposits = data?.data ?? [];
  const meta = data?.meta;
  const isActionPending = approve.isPending || reject.isPending || remove.isPending;
  const pendingActionId =
    isActionPending && pendingAction ? pendingAction.deposit.id : null;

  const confirmTitle = pendingAction?.type === "approve"
    ? t("page.deposits.confirm.approveTitle")
    : pendingAction?.type === "reject"
      ? t("page.deposits.confirm.rejectTitle")
      : t("page.deposits.confirm.deleteTitle");

  const confirmMessage = pendingAction
    ? pendingAction.type === "approve"
      ? t("page.deposits.confirm.approveMessage", {
          id: String(pendingAction.deposit.id),
          username: pendingAction.deposit.username,
          amount: formatAmount(pendingAction.deposit.num),
          coin: pendingAction.deposit.coin.toUpperCase(),
        })
      : pendingAction.type === "reject"
        ? t("page.deposits.confirm.rejectMessage", {
            id: String(pendingAction.deposit.id),
            username: pendingAction.deposit.username,
          })
        : t("page.deposits.confirm.deleteMessage", {
            id: String(pendingAction.deposit.id),
          })
    : "";

  const confirmLabel = pendingAction?.type === "approve"
    ? t("action.approve")
    : pendingAction?.type === "reject"
      ? t("action.reject")
      : t("common.delete");

  return (
    <div className="space-y-6">
      <PageHeader titleKey="page.deposits.title" descriptionKey="page.deposits.description" />

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

      {isLoading ? <DepositListSkeleton /> : null}

      {isError ? (
        <ErrorState
          message={error instanceof Error ? error.message : t("page.deposits.loadFailed")}
          retry={() => refetch()}
        />
      ) : null}

      {!isLoading && !isError && deposits.length === 0 ? (
        <EmptyState titleKey="page.deposits.noResults" descriptionKey="common.noResultsHint" />
      ) : null}

      {!isLoading && !isError && deposits.length > 0 ? (
        <>
          {meta ? <PageMetaBar meta={meta} isFetching={isFetching} /> : null}
          <DepositList
            deposits={deposits}
            pendingActionId={pendingActionId}
            onApprove={(deposit) => {
              setActionError(null);
              setPendingAction({ deposit, type: "approve" });
            }}
            onReject={(deposit) => {
              setActionError(null);
              setPendingAction({ deposit, type: "reject" });
            }}
            onDelete={(deposit) => {
              setActionError(null);
              setPendingAction({ deposit, type: "delete" });
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
