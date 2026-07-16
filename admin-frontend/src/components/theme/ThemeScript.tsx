import { DEFAULT_THEME, THEME_STORAGE_KEY } from "@/lib/theme/types";

export function ThemeScript() {
  const script = `(function(){try{var k=${JSON.stringify(THEME_STORAGE_KEY)};var d=${JSON.stringify(DEFAULT_THEME)};var t=localStorage.getItem(k);document.documentElement.setAttribute("data-theme",t==="light"||t==="dark"?t:d);document.documentElement.style.colorScheme=t==="light"?"light":"dark";}catch(e){document.documentElement.setAttribute("data-theme",${JSON.stringify(DEFAULT_THEME)});}})();`;

  return <script dangerouslySetInnerHTML={{ __html: script }} />;
}
