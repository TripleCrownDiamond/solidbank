"use client";

import { ClerkProvider } from "@clerk/nextjs";
import { dark } from "@clerk/themes";
import { useTheme } from "next-themes";
import { useEffect, useState } from "react";
import { getClerkLocale } from "@/lib/clerkLocale";

export function ClerkClientProvider({
  children,
  locale,
}: {
  children: React.ReactNode;
  locale: string;
}) {
  const { resolvedTheme } = useTheme();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  // Avoid hydration mismatch
  if (!mounted) return null;

  return (
    <ClerkProvider
      localization={getClerkLocale(locale)}
      appearance={{
        baseTheme: resolvedTheme === "dark" ? dark : undefined,
        layout: {
          logoImageUrl: "/icon_blue.svg",
          logoLinkUrl: "/",
          logoPlacement: "inside",
          socialButtonsPlacement: "bottom",
        },
      }}
    >
      {children}
    </ClerkProvider>
  );
}
