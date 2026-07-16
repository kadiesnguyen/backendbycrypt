import type { Metadata } from "next";
import { Be_Vietnam_Pro } from "next/font/google";
import { ThemeScript } from "@/components/theme/ThemeScript";
import { Providers } from "./providers";
import "./globals.css";
import { DEFAULT_THEME } from "@/lib/theme/types";

const beVietnamPro = Be_Vietnam_Pro({
  subsets: ["latin", "vietnamese"],
  weight: ["400", "500", "600", "700"],
  display: "swap",
});

export const metadata: Metadata = {
  title: "WNSKCEX Admin",
  description: "Admin dashboard for WNSKCEX",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="vi" data-theme={DEFAULT_THEME} suppressHydrationWarning>
      <head>
        <ThemeScript />
      </head>
      <body className={beVietnamPro.className}>
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
