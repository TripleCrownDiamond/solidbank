"use client";

import { SignIn } from "@clerk/nextjs";
import { Skeleton } from "@/components/ui/skeleton";
import { useState, useEffect } from "react";
import { AuthPagesNavbar } from "@/components/auth-pages-navbar";
import Link from "next/link";
import { useI18n } from "../../../../locales/client";

export default function Page() {
  const [isLoading, setIsLoading] = useState(true);
  const t = useI18n();

  useEffect(() => {
    const timer = setTimeout(() => setIsLoading(false), 1000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <main>
      <AuthPagesNavbar />

      <section className="flex min-h-dvh flex-col items-center justify-center px-4 py-16">
        {isLoading ? (
          <div className="w-full max-w-md space-y-4">
            <Skeleton className="h-12 w-full rounded-md mb-6" />
            <Skeleton className="h-10 w-full rounded-md" />
            <Skeleton className="h-10 w-full rounded-md" />
            <Skeleton className="h-10 w-3/4 mx-auto rounded-md mt-4" />
            <Skeleton className="h-4 w-32 mx-auto rounded-md mt-6" />
          </div>
        ) : (
          <div className="w-full max-w-md flex flex-col items-center">
            <SignIn />
            <div className="mt-4 w-full flex justify-center">
              <Link href="/forgot-password" className="text-sm text-primary hover:underline">
                {t("forgotPassword")}
              </Link>
            </div>
          </div>
        )}
      </section>
    </main>
  );
}