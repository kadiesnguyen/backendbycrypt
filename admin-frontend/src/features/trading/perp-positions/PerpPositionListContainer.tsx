"use client";

import {
  EmptyState,
  PageHeader,
  PageMetaBar,
  PaginationNav,
} from "@/components/list/ListPageParts";
import { ConfirmDialog } from "@/components/ui/ConfirmDialog";
import { ErrorState } from "@/components/ui/ErrorState";
import { contractKongykLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import Link from "next/link";
import { Suspense, useCallback, useMemo, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { PerpPositionList } from "./PerpPositionList";
import { PerpPositionListSkeleton } from "./PerpPositionListSkeleton";
import { PerpSettingsPanel } from "./PerpSettingsPanel";
import { usePerpPositionActions } from "./usePerpPositionActions";
import { usePerpPositions } from "./usePerpPositions";
import type { PerpPosition } from "./types";

type PendingAction = { position: PerpPosition; kongyk: 0 | 1 | 2 };

type Props = { embedded?: boolean };

function PerpListView({
  embedded,
  page,
  perPage,
  scope,
  onPageChange,
}: {
  embedded: boolean;
  page: number;
  perPage: number;
  scope: "open" | "closed" | "all";
  onPageChange?: (page: number) => void;
}) {
  const { t } = useI18n();
  const [pendingAction, setPendingAction] = useState<PendingAction | null>(null);
  const [pendingSettle, setPendingSettle] = useState<PerpPosition | null>(null);
  const [actionError, setActionError] = useState<string | null>(null);

  const queryParams = useMemo(
    () => ({ page: page > 0 ? page : 1, per_page: perPage, scope }),
    [page, perPage, scope],
  );

  const { data, isLoading, isError, error, refetch, isFetching } = usePerpPositions(queryParams);
  const { setWinLoss, settle } = usePerpPositionActions();

  const positions = data?.data ?? [];
  const meta = data?.meta;

  const handleConfirmWinLoss = async () => {
    if (!pendingAction) return;
    setActionError(null);
    try {
      await setWinLoss.mutateAsync({
        id: pendingAction.position.id,
        kongyk: pendingAction.kongyk,
      });
      setPendingAction(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const handleConfirmSettle = async () => {
    if (!pendingSettle) return;
    setActionError(null);
    try {
      await settle.mutateAsync(pendingSettle.id);
      setPendingSettle(null);
    } catch (err) {
      setActionError(err instanceof Error ? err.message : t("common.actionFailed"));
    }
  };

  const pendingId =
    setWinLoss.isPending && pendingAction
      ? pendingAction.position.id
      : settle.isPending && pendingSettle
        ? pendingSettle.id
        : null;

  const listContent = isLoading ? (
    <PerpPositionListSkeleton />
  ) : isError ? (
    <ErrorState
      message={error instanceof Error ? error.message : t("common.loadFailed")}
      retry={() => refetch()}
    />
  ) : positions.length === 0 ? (
    <EmptyState titleKey="page.perp.empty" />
  ) : (
    <>
      <PerpPositionList
        positions={positions}
        pendingActionId={pendingId}
        embedded={embedded}
        readonly={scope !== "open"}
        onSetWinLoss={(position, kongyk) => setPendingAction({ position, kongyk })}
        onSettle={(position) => setPendingSettle(position)}
      />
      {!embedded && meta && onPageChange ? (
        <PaginationNav meta={meta} onPageChange={onPageChange} isFetching={isFetching} />
      ) : null}
    </>
  );

  const confirmDialogs = (
    <>
      <ConfirmDialog
        isOpen={pendingAction !== null}
        title={t("page.perp.confirmControlTitle")}
        message={
          pendingAction
            ? `${pendingAction.position.username} · ${pendingAction.position.symbol} · ${contractKongykLabel(t, pendingAction.kongyk)}`
            : ""
        }
        confirmLabel={t("common.confirm")}
        variant={pendingAction?.kongyk === 2 ? "danger" : "default"}
        isPending={setWinLoss.isPending}
        onConfirm={handleConfirmWinLoss}
        onCancel={() => {
          if (!setWinLoss.isPending) setPendingAction(null);
        }}
      />
      <ConfirmDialog
        isOpen={pendingSettle !== null}
        title={t("action.settleOrder")}
        message={
          pendingSettle ? `#${pendingSettle.id} · ${pendingSettle.symbol} · ${pendingSettle.username}` : ""
        }
        confirmLabel={t("action.settleOrder")}
        isPending={settle.isPending}
        onConfirm={handleConfirmSettle}
        onCancel={() => {
          if (!settle.isPending) setPendingSettle(null);
        }}
      />
      {actionError ? (
        <p className="text-sm text-danger" role="alert">
          {actionError}
        </p>
      ) : null}
    </>
  );

  if (embedded) {
    return (
      <section aria-label={t("page.dashboard.perpPositions")} className="space-y-4">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
          <div className="min-w-0">
            <h2 className="text-base font-semibold text-foreground">{t("page.dashboard.perpPositions")}</h2>
            <p className="mt-0.5 text-sm text-muted">{t("page.dashboard.perpPositionsHint")}</p>
          </div>
          <Link
            href="/trading/perp"
            className="shrink-0 text-sm font-medium text-primary transition hover:opacity-80"
          >
            {t("page.dashboard.viewAll")}
          </Link>
        </div>
        {listContent}
        {confirmDialogs}
      </section>
    );
  }

  return (
    <div className="space-y-6">
      {scope === "open" ? (
        <>
          <PageHeader titleKey="page.perp.title" descriptionKey="page.perp.description" />
          <PerpSettingsPanel />
        </>
      ) : null}
      {listContent}
      {confirmDialogs}
    </div>
  );
}

function PerpPaginated({ scope }: { scope: "open" | "closed" | "all" }) {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  const page = Number(searchParams.get("page") ?? "1");

  const updateParams = useCallback(
    (updates: Record<string, string | null>) => {
      const next = new URLSearchParams(searchParams.toString());
      for (const [key, value] of Object.entries(updates)) {
        if (value === null || value === "") next.delete(key);
        else next.set(key, value);
      }
      router.push(`${pathname}?${next.toString()}`);
    },
    [pathname, router, searchParams],
  );

  return (
    <PerpListView
      embedded={false}
      page={page}
      perPage={15}
      scope={scope}
      onPageChange={(p) => updateParams({ page: String(p) })}
    />
  );
}

export function PerpPositionListContainer({ embedded = false }: Props) {
  if (embedded) {
    return <PerpListView embedded page={1} perPage={10} scope="open" />;
  }

  return (
    <Suspense fallback={<PerpPositionListSkeleton />}>
      <PerpPaginated scope="open" />
    </Suspense>
  );
}

export function PerpPositionHistoryContainer() {
  return (
    <Suspense fallback={<PerpPositionListSkeleton />}>
      <PerpPaginated scope="closed" />
    </Suspense>
  );
}
