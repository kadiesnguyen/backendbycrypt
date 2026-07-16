"use client";

import type { AdminMenuTree } from "@/features/auth/types";
import { SidebarBrand, SidebarNav } from "./SidebarNav";

type AppShellSidebarProps = {
  menuTree: AdminMenuTree;
};

export function AppShellSidebar({ menuTree }: AppShellSidebarProps) {
  return (
    <aside
      aria-label="Admin navigation"
      className="hidden w-80 shrink-0 border-r border-border bg-surface md:block"
    >
      <SidebarBrand />
      <SidebarNav menuTree={menuTree} />
    </aside>
  );
}

type AppShellMobileSidebarProps = {
  menuTree: AdminMenuTree;
  open: boolean;
  onClose: () => void;
};

export function AppShellMobileSidebar({ menuTree, open, onClose }: AppShellMobileSidebarProps) {
  return (
    <>
      <div
        className={`fixed inset-0 z-40 bg-[var(--color-overlay)] transition-opacity duration-200 md:hidden ${
          open ? "opacity-100" : "pointer-events-none opacity-0"
        }`}
        aria-hidden={!open}
        onClick={onClose}
      />
      <aside
        id="mobile-sidebar"
        aria-label="Admin navigation"
        aria-hidden={!open}
        className={`fixed inset-y-0 left-0 z-50 flex w-80 max-w-[min(20rem,85vw)] flex-col border-r border-border bg-surface transition-transform duration-200 ease-out md:hidden ${
          open ? "translate-x-0" : "-translate-x-full"
        }`}
      >
        <SidebarBrand />
        <div className="min-h-0 flex-1 overflow-y-auto overscroll-contain">
          <SidebarNav menuTree={menuTree} onNavigate={onClose} />
        </div>
      </aside>
    </>
  );
}
