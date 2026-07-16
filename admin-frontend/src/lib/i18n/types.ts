export type Locale = "vi" | "en";

export type TranslationParams = Record<string, string | number>;

export type Dictionary = Record<string, string>;

export const LOCALE_STORAGE_KEY = "admin_locale";
export const DEFAULT_LOCALE: Locale = "vi";

export function isLocale(value: string): value is Locale {
  return value === "vi" || value === "en";
}

export function interpolate(template: string, params?: TranslationParams): string {
  if (!params) {
    return template;
  }

  return template.replace(/\{(\w+)\}/g, (_, key: string) =>
    key in params ? String(params[key]) : `{${key}}`,
  );
}
