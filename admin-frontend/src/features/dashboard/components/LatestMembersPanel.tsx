import type { DashboardMember } from "../types";
import { formatRelativeTime } from "../format";
import { DashboardCard } from "./DashboardCard";

type LatestMembersPanelProps = {
  title: string;
  viewAllLabel: string;
  members: DashboardMember[];
  emptyLabel: string;
  statusActive: string;
  statusInactive: string;
  agentBadge: string;
};

export function LatestMembersPanel({
  title,
  viewAllLabel,
  members,
  emptyLabel,
  statusActive,
  statusInactive,
  agentBadge,
}: LatestMembersPanelProps) {
  return (
    <DashboardCard
      title={title}
      action={{ label: viewAllLabel, href: "/users" }}
      bodyClassName="overflow-auto p-0"
      className="h-full min-h-[15.5rem]"
    >
      {members.length === 0 ? (
        <p className="px-4 py-6 text-sm text-muted">{emptyLabel}</p>
      ) : (
        <ul className="divide-y divide-border/60">
          {members.map((member) => (
            <li key={member.id} className="flex items-center gap-3 px-4 py-2.5">
              <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-surface-elevated text-xs font-semibold uppercase text-primary">
                {member.username.slice(0, 2)}
              </div>
              <div className="min-w-0 flex-1">
                <div className="flex items-center gap-2">
                  <p className="break-all text-sm font-medium text-foreground">{member.username}</p>
                  {member.is_agent === 1 ? (
                    <span className="shrink-0 rounded-full bg-primary/15 px-1.5 py-0.5 text-xs font-medium uppercase text-primary">
                      {agentBadge}
                    </span>
                  ) : null}
                </div>
                <p className="text-xs text-muted">{formatRelativeTime(member.addtime)} ago</p>
              </div>
              <span
                className={`shrink-0 rounded-full px-2 py-0.5 text-xs font-medium ${
                  member.status === 1
                    ? "bg-success/15 text-success"
                    : "bg-muted-chip text-muted"
                }`}
              >
                {member.status === 1 ? statusActive : statusInactive}
              </span>
            </li>
          ))}
        </ul>
      )}
    </DashboardCard>
  );
}
