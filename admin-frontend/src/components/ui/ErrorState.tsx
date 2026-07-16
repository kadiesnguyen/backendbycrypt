type ErrorStateProps = {
  title?: string;
  message: string;
  retry?: () => void;
};

export function ErrorState({
  title = "Something went wrong",
  message,
  retry,
}: ErrorStateProps) {
  return (
    <div
      role="alert"
      className="rounded-lg border border-danger/40 bg-surface p-6 text-center"
    >
      <h2 className="text-base font-semibold text-foreground">{title}</h2>
      <p className="mt-2 text-sm text-muted">{message}</p>
      {retry ? (
        <button
          type="button"
          onClick={retry}
          className="mt-4 rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90"
        >
          Try again
        </button>
      ) : null}
    </div>
  );
}
