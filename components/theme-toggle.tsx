"use client";

import { MoonIcon, SunIcon } from "@heroicons/react/24/outline";
import { useTheme } from "next-themes";
import { useI18n } from "../locales/client";
import { useEffect, useState } from "react";

import { Button } from "./ui/button";

export function ThemeToggle() {
  const { setTheme, theme, resolvedTheme } = useTheme();
  const t = useI18n();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  if (!mounted) {
    return (
      <Button
        variant="outline"
        size="icon"
        className="rounded-full w-10 h-10 border border-border"
        aria-label={t("toggleTheme")}
      >
        <div className="h-5 w-5" />
      </Button>
    );
  }

  const currentTheme = theme === "system" ? resolvedTheme : theme;

  return (
    <Button
      variant="outline"
      size="icon"
      className="rounded-full w-10 h-10 border border-border transition-colors hover:bg-muted/50"
      onClick={() => setTheme(currentTheme === "light" ? "dark" : "light")}
      aria-label={t("toggleTheme")}
    >
      {currentTheme === "light" ? (
        <MoonIcon className="h-5 w-5" />
      ) : (
        <SunIcon className="h-5 w-5" />
      )}
    </Button>
  );
}