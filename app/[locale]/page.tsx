"use client";

import { useI18n } from "../../locales/client"; // Assuming this path is correct
import { Skeleton } from "@/components/ui/skeleton"; // Import Skeleton
import { useState, useEffect } from "react"; // For loading state simulation
import { Navbar } from "@/components/navbar";
import Link from "next/link";

const Home = () => {
  const t = useI18n();
  const [isLoading, setIsLoading] = useState(true); // Example loading state

  useEffect(() => {
    // Simulate data fetching or initial loading
    const timer = setTimeout(() => setIsLoading(false), 2500); // Adjust timing as needed
    return () => clearTimeout(timer);
  });

  if (isLoading) {
    return (
      <main>
        {/* Skeleton for Navbar */}
        <div className="fixed top-0 left-0 right-0 z-10 bg-background/80 backdrop-blur-sm border-b border-border">
          <div className="container mx-auto px-4 py-3 flex items-center justify-between">
            <Skeleton className="h-8 w-32 rounded" />
            <div className="flex items-center gap-4">
              <Skeleton className="h-8 w-16 rounded" />
              <Skeleton className="h-8 w-20 rounded" />
              <Skeleton className="h-10 w-10 rounded-full border border-border" />
            </div>
          </div>
        </div>
        <section className="flex min-h-dvh flex-col items-center justify-center pt-16">
          <div className="container text-center">
            {/* Skeleton for <h1> */}
            <Skeleton className="h-10 w-3/5 mx-auto mb-6 rounded" />
            {/* Skeleton for <p> */}
            <Skeleton className="h-4 w-full max-w-md mx-auto mb-2 rounded" />
            <Skeleton className="h-4 w-4/5 max-w-md mx-auto rounded" />
            <div className="mt-8 flex flex-wrap justify-center gap-4">
              {/* Skeleton for "Get Started" button */}
              <Skeleton className="h-[42px] w-[120px] rounded-md" />{" "}
              {/* Adjusted width based on typical button text */}
              {/* Skeleton for "Learn More" button */}
              <Skeleton className="h-[42px] w-[120px] rounded-md" />{" "}
              {/* Adjusted width */}
            </div>
          </div>
        </section>
      </main>
    );
  }

  // Original content when not loading
  return (
    <main>
      <Navbar />
      <section className="flex min-h-dvh flex-col items-center justify-center pt-16">
        <div className="container text-center">
          <h1 className="mb-6 font-heading text-4xl md:text-5xl font-bold">
            {t("hello")}
          </h1>{" "}
          {/* Example: Apply heading font */}
          <p className="mx-auto max-w-md text-muted-foreground">
            {t("welcome")}
          </p>
          <div className="mt-8 flex flex-wrap justify-center gap-4">
            <Link
              href="/sign-up"
              className="rounded-md bg-primary px-6 py-3 text-primary-foreground dark:text-white transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring no-underline"
            >
              {t("getStarted")}
            </Link>
            <Link
              href="/about"
              className="rounded-md border border-border bg-background text-foreground px-6 py-3 transition-colors hover:bg-muted/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring no-underline"
            >
              {t("learnMore")}
            </Link>
          </div>
        </div>
      </section>
    </main>
  );
};

export default Home;
