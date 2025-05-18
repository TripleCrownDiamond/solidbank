"use client";

import { SignUp } from "@clerk/nextjs";
import { Skeleton } from "@/components/ui/skeleton";
import { useState, useEffect } from "react";
import { AuthPagesNavbar } from "@/components/auth-pages-navbar";

export default function Page() {
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => setIsLoading(false), 1000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <main>
      <AuthPagesNavbar />

      {/* Section avec des marges en haut et en bas */}
      <section className="flex min-h-dvh flex-col items-center justify-center px-4 py-16">
        {isLoading ? (
          <div className="w-full max-w-md space-y-4">
            <Skeleton className="h-12 w-full rounded-md mb-6" />
            <Skeleton className="h-10 w-full rounded-md" />
            <Skeleton className="h-10 w-full rounded-md" />
            <Skeleton className="h-10 w-3/4 mx-auto rounded-md mt-4" />
          </div>
        ) : (
          <SignUp />
        )}
      </section>
    </main>
  );
}
