import { LoginForm } from "@/features/auth/LoginForm";
import { ThemeToggle } from "@/components/theme/ThemeToggle";

export default function LoginPage() {
  return (
    <div className="flex min-h-screen flex-col bg-background">
      <div className="flex justify-end p-4">
        <ThemeToggle compact />
      </div>
      <div className="flex flex-1 items-center justify-center px-4 pb-8">
      <section
        aria-labelledby="login-heading"
        className="w-full max-w-md rounded-lg border border-border bg-surface p-6 shadow-sm md:p-8"
      >
        <LoginForm />
      </section>
      </div>
    </div>
  );
}
