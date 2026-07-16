import type { Config } from "tailwindcss";

const config: Config = {
  content: ["./src/**/*.{js,ts,jsx,tsx,mdx}"],
  theme: {
    extend: {
      colors: {
        primary: "var(--color-primary)",
        background: "var(--color-bg)",
        foreground: "var(--color-text)",
        muted: "var(--color-text-muted)",
        surface: "var(--color-surface)",
        "surface-elevated": "var(--color-surface-elevated)",
        border: "var(--color-border)",
        "muted-chip": "var(--color-muted-chip)",
        overlay: "var(--color-overlay)",
        danger: "var(--color-error)",
        success: "var(--color-success)",
      },
      borderRadius: {
        DEFAULT: "var(--radius-md)",
        sm: "var(--radius-sm)",
        lg: "var(--radius-lg)",
        card: "var(--radius-card)",
        xl: "var(--radius-card)",
      },
    },
  },
  plugins: [],
};

export default config;
