import type { DashboardAgent } from "../types";
import { DashboardCard } from "./DashboardCard";

type TopAgentsPanelProps = {
  title: string;
  viewAllLabel: string;
  agents: DashboardAgent[];
  emptyLabel: string;
};

export function TopAgentsPanel({ title, viewAllLabel, agents, emptyLabel }: TopAgentsPanelProps) {
  return (
    <DashboardCard
      title={title}
      action={{ label: viewAllLabel, href: "/users/agents" }}
      bodyClassName="overflow-auto p-0"
      className="h-full min-h-[15.5rem]"
    >
      {agents.length === 0 ? (
        <p className="px-4 py-6 text-sm text-muted">{emptyLabel}</p>
      ) : (
        <ol className="divide-y divide-border/60">
          {agents.map((agent, index) => (
            <li key={agent.id} className="flex items-center gap-3 px-4 py-2.5">
              <span
                className={`flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold tabular-nums ${
                  index === 0
                    ? "bg-primary/20 text-primary"
                    : index === 1
                      ? "bg-surface-elevated text-foreground"
                      : index === 2
                        ? "bg-muted-chip text-muted"
                        : "bg-transparent text-muted"
                }`}
              >
                {index + 1}
              </span>
              <div className="min-w-0 flex-1">
                <p className="break-all text-sm font-medium text-foreground">{agent.username}</p>
                <p className="break-all text-xs text-muted">
                  {agent.invit ? `#${agent.invit}` : "—"}
                </p>
              </div>
              <span className="shrink-0 rounded-md bg-surface-elevated px-2 py-0.5 text-xs font-semibold tabular-nums text-primary">
                {agent.referrals}
              </span>
            </li>
          ))}
        </ol>
      )}
    </DashboardCard>
  );
}
