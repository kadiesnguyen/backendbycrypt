export type Theme = "dark" | "light";

export const THEME_STORAGE_KEY = "wnskcex-admin-theme";
export const DEFAULT_THEME: Theme = "dark";

export function isTheme(value: string | null | undefined): value is Theme {
  return value === "dark" || value === "light";
}
