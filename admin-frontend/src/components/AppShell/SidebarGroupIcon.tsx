import type { ReactNode } from "react";

type SidebarGroupIconProps = {
  groupName: string;
  className?: string;
};

function IconShell({ children, className }: { children: ReactNode; className: string }) {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      className={className}
      aria-hidden="true"
    >
      {children}
    </svg>
  );
}

const GROUP_ICONS: Record<string, ReactNode> = {
  "Quick Actions": (
    <>
      <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z" />
    </>
  ),
  "User Management": (
    <>
      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
      <circle cx="9" cy="7" r="4" />
      <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
    </>
  ),
  "Quick Contract": (
    <>
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
      <path d="M14 2v6h6" />
      <path d="M8 13h8" />
      <path d="M8 17h5" />
    </>
  ),
  "Financial Management": (
    <>
      <rect width="20" height="14" x="2" y="5" rx="2" />
      <path d="M2 10h20" />
    </>
  ),
  "Miner Management": (
    <>
      <path d="M12 3v3" />
      <path d="M6 9h12" />
      <path d="M8 9 5 21h14l-3-12" />
      <path d="M9 14h6" />
    </>
  ),
  "New Purchase Management": (
    <>
      <circle cx="12" cy="12" r="10" />
      <path d="M12 8v8" />
      <path d="M8 12h8" />
    </>
  ),
  System: (
    <>
      <circle cx="12" cy="12" r="3" />
      <path d="M12 1v2" />
      <path d="M12 21v2" />
      <path d="m4.22 4.22 1.42 1.42" />
      <path d="m18.36 18.36 1.42 1.42" />
      <path d="M1 12h2" />
      <path d="M21 12h2" />
      <path d="m4.22 19.78 1.42-1.42" />
      <path d="m18.36 5.64 1.42-1.42" />
    </>
  ),
};

const DEFAULT_ICON = (
  <>
    <circle cx="12" cy="12" r="10" />
    <path d="M12 8v4" />
    <path d="M12 16h.01" />
  </>
);

export function SidebarGroupIcon({ groupName, className = "h-4 w-4 shrink-0 text-primary" }: SidebarGroupIconProps) {
  return <IconShell className={className}>{GROUP_ICONS[groupName] ?? DEFAULT_ICON}</IconShell>;
}
