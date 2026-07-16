type KpiCardProps = {
  label: string;
  value: string;
  sublabel?: string;
  accent?: "gold" | "green" | "blue" | "red" | "neutral";
};

const accentBorderClass = {
  gold: "dashboard-kpi-accent-gold",
  green: "dashboard-kpi-accent-green",
  blue: "dashboard-kpi-accent-blue",
  red: "dashboard-kpi-accent-red",
  neutral: "dashboard-kpi-accent-neutral",
} as const;

const accentValueClass = {
  gold: "text-primary",
  green: "text-success",
  blue: "text-primary",
  red: "text-danger",
  neutral: "text-foreground",
} as const;

export function KpiCard({ label, value, sublabel, accent = "neutral" }: KpiCardProps) {
  return (
    <article
      className={`dashboard-card flex h-full min-h-[5.5rem] flex-col justify-center px-4 py-3 ${accentBorderClass[accent]}`}
    >
      <p className="text-xs font-bold uppercase tracking-wide text-muted">{label}</p>
      <p className={`mt-1 truncate text-lg font-semibold tabular-nums ${accentValueClass[accent]}`}>
        {value}
      </p>
      {sublabel ? <p className="mt-0.5 truncate text-xs text-muted">{sublabel}</p> : null}
    </article>
  );
}
