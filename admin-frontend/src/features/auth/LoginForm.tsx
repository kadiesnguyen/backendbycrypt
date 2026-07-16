"use client";

import { FormEvent, useId, useState } from "react";
import { ApiError } from "@/lib/api-client";
import { useI18n } from "@/lib/i18n/useI18n";
import { useLogin } from "@/features/auth/useAuth";

export function LoginForm() {
  const { t } = useI18n();
  const usernameId = useId();
  const passwordId = useId();
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const login = useLogin();

  const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    login.mutate({ username, password });
  };

  const errorMessage =
    login.error instanceof ApiError
      ? login.error.message
      : login.error
        ? t("common.loadFailed")
        : null;

  return (
    <form
      onSubmit={handleSubmit}
      className="w-full max-w-sm space-y-4"
      aria-labelledby="login-heading"
      noValidate
    >
      <div className="space-y-1 text-center">
        <h1 id="login-heading" className="text-2xl font-semibold text-foreground">
          {t("auth.loginTitle")}
        </h1>
        <p className="text-sm text-muted">{t("auth.loginDescription")}</p>
      </div>

      <div className="space-y-3">
        <div>
          <label htmlFor={usernameId} className="mb-1 block text-sm text-foreground">
            {t("common.username")}
          </label>
          <input
            id={usernameId}
            name="username"
            type="text"
            autoComplete="username"
            required
            value={username}
            onChange={(event) => setUsername(event.target.value)}
            className="w-full rounded border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted focus:border-primary"
            placeholder="admin"
          />
        </div>

        <div>
          <label htmlFor={passwordId} className="mb-1 block text-sm text-foreground">
            {t("auth.password")}
          </label>
          <input
            id={passwordId}
            name="password"
            type="password"
            autoComplete="current-password"
            required
            value={password}
            onChange={(event) => setPassword(event.target.value)}
            className="w-full rounded border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted focus:border-primary"
            placeholder="••••••••"
          />
        </div>
      </div>

      {errorMessage ? (
        <p role="alert" className="rounded border border-danger/40 bg-surface px-3 py-2 text-sm text-danger">
          {errorMessage}
        </p>
      ) : null}

      <button
        type="submit"
        disabled={login.isPending}
        aria-busy={login.isPending}
        className="w-full rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60"
      >
        {login.isPending ? t("auth.signingIn") : t("auth.signIn")}
      </button>
    </form>
  );
}
