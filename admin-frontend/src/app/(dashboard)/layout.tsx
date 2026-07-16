"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";
import { AppShell } from "@/components/AppShell/AppShell";
import { AppShellSkeleton } from "@/components/AppShell/AppShellSkeleton";
import { ErrorState } from "@/components/ui/ErrorState";
import { ApiError, getAdminToken } from "@/lib/api-client";
import { useI18n } from "@/lib/i18n/useI18n";
import { useAuthSession } from "@/features/auth/useAuth";
import { useClientMounted } from "@/hooks/useClientMounted";

type DashboardLayoutProps = {
  children: React.ReactNode;
};

export default function DashboardLayout({ children }: DashboardLayoutProps) {
  const router = useRouter();
  const mounted = useClientMounted();
  const { t } = useI18n();
  const { user, menuTree, isLoading, isError, error, refetch } = useAuthSession();

  useEffect(() => {
    if (!mounted) {
      return;
    }
    if (!getAdminToken()) {
      router.replace("/login");
    }
  }, [mounted, router]);

  if (!mounted) {
    return <AppShellSkeleton />;
  }

  if (!getAdminToken()) {
    return <AppShellSkeleton />;
  }

  if (isLoading) {
    return <AppShellSkeleton />;
  }

  if (isError || !user) {
    const message =
      error instanceof ApiError && error.status === 401
        ? t("auth.sessionExpired")
        : error instanceof Error
          ? error.message
          : t("common.loadFailed");

    return (
      <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <div className="w-full max-w-md">
          <ErrorState
            title={t("auth.sessionUnavailable")}
            message={message}
            retry={() => {
              void refetch();
            }}
          />
        </div>
      </div>
    );
  }

  return (
    <AppShell user={user} menuTree={menuTree}>
      {children}
    </AppShell>
  );
}
