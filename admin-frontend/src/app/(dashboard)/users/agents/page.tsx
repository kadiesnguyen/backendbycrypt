import { Suspense } from "react";
import { AgentListContainer } from "@/features/users/agents/AgentListContainer";
import { AgentListSkeleton } from "@/features/users/agents/AgentListSkeleton";

export default function AgentsPage() {
  return (
    <Suspense fallback={<AgentListSkeleton />}>
      <AgentListContainer />
    </Suspense>
  );
}
