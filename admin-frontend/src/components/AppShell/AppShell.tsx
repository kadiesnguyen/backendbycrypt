"use client";

import { useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import type { AdminMenuTree, AdminUser } from "@/features/auth/types";
import { ContractOrderAlert } from "@/features/trading/orders/contract-order-alert/ContractOrderAlert";
import { AppShellHeader } from "./AppShellHeader";
import { AppShellMobileSidebar, AppShellSidebar } from "./AppShellSidebar";

type AppShellProps = {
  user: AdminUser;
  menuTree: AdminMenuTree;
  children: React.ReactNode;
};

export function AppShell({ user, menuTree, children }: AppShellProps) {
  const pathname = usePathname();
  const [mobileSidebarOpen, setMobileSidebarOpen] = useState(false);

  useEffect(() => {
    setMobileSidebarOpen(false);
  }, [pathname]);

  useEffect(() => {
    if (!mobileSidebarOpen) {
      return;
    }

    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";

    return () => {
      document.body.style.overflow = previousOverflow;
    };
  }, [mobileSidebarOpen]);

  return (
    <div className="flex min-h-screen bg-background">
      <AppShellSidebar menuTree={menuTree} />
      <AppShellMobileSidebar
        menuTree={menuTree}
        open={mobileSidebarOpen}
        onClose={() => setMobileSidebarOpen(false)}
      />
      <div className="flex min-w-0 flex-1 flex-col">
        <AppShellHeader
          user={user}
          menuOpen={mobileSidebarOpen}
          onMenuToggle={() => setMobileSidebarOpen((open) => !open)}
        />
        <main id="main-content" className="min-w-0 flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
          {children}
        </main>
      </div>
      <ContractOrderAlert />
    </div>
  );
}
