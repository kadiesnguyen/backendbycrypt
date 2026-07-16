export default function LoginLoading() {
  return (
    <div
      className="flex min-h-screen items-center justify-center bg-background px-4"
      aria-busy="true"
      aria-label="Loading sign in"
    >
      <div className="w-full max-w-md space-y-4 rounded-lg border border-border bg-surface p-6 md:p-8">
        <div className="skeleton mx-auto h-8 w-40" />
        <div className="skeleton h-4 w-full" />
        <div className="skeleton h-10 w-full" />
        <div className="skeleton h-10 w-full" />
        <div className="skeleton h-10 w-full" />
      </div>
    </div>
  );
}
