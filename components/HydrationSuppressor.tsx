"use client";

import { useEffect, useState } from "react";

export function HydrationSuppressor({
  children,
}: {
  children: React.ReactNode;
}) {
  const [isMounted, setIsMounted] = useState(false);

  useEffect(() => {
    setIsMounted(true);
  }, []);

  return (
    <>
      <style jsx global>{`
        html {
          visibility: ${isMounted ? "visible" : "hidden"};
        }
      `}</style>
      {children}
    </>
  );
}
