"use client";

import Link from "next/link";
import Image from "next/image";
import { useI18n } from "../locales/client";
import { ThemeToggle } from "./theme-toggle";
import { UserButton, SignInButton } from "@clerk/nextjs";
import { useAuth } from "@clerk/nextjs";
import { useTheme } from "next-themes";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/outline";
import { useState } from "react";

export function Navbar() {
  const t = useI18n();
  const { isSignedIn } = useAuth();
  const { theme } = useTheme();
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <header className="fixed top-0 left-0 right-0 z-10 bg-sidebar/80 backdrop-blur-sm border-b border-sidebar-border transition-colors duration-300 dark:bg-sidebar dark:border-sidebar-border">
      <div className="container mx-auto px-4 py-3 flex items-center justify-between">
        <Link href="/" className="font-heading text-xl font-bold text-sidebar-foreground dark:text-sidebar-foreground no-underline">
          <Image
            src="/logo_blue.svg"
            alt="Logo"
            width={160}
            height={40}
            className="inline-block"
          />
        </Link>

        {/* Desktop Navigation */}
        <nav className="hidden md:flex items-center gap-6">
          {["home", "about", "services"].map((item) => (
            <Link 
              key={item}
              href="#" 
              className="text-sm text-sidebar-foreground hover:text-sidebar-accent-foreground transition-colors duration-300 dark:text-sidebar-foreground dark:hover:text-sidebar-accent-foreground px-4 py-2 rounded-full hover:bg-sidebar-primary/20 dark:hover:bg-sidebar-primary/30"
            >
              {t(`nav.${item}`) || item.charAt(0).toUpperCase() + item.slice(1)}
            </Link>
          ))}
        </nav>

        {/* Desktop Auth & Theme */}
        <div className="hidden md:flex items-center gap-4">
          {isSignedIn ? (
            <UserButton afterSignOutUrl="/" />
          ) : (
            <SignInButton>
              <Link href="/sign-in" className="rounded-md bg-sidebar-primary px-4 py-2 text-sm text-sidebar-primary-foreground transition-colors duration-300 hover:bg-sidebar-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sidebar-ring dark:bg-sidebar-primary dark:text-white">
                {t("nav.signIn") || "Se Connecter"}
              </Link>
            </SignInButton>
          )}
          <ThemeToggle />
        </div>

        {/* Mobile Controls */}
        <div className="flex md:hidden items-center gap-2">
          {isSignedIn && <UserButton afterSignOutUrl="/" />}
          <ThemeToggle />
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="rounded-full w-10 h-10 flex items-center justify-center border border-border transition-colors hover:bg-muted/50"
            aria-label="Toggle menu"
          >
            {isMenuOpen ? (
              <XMarkIcon className="h-5 w-5" />
            ) : (
              <Bars3Icon className="h-5 w-5" />
            )}
          </button>
        </div>

        {/* Mobile Off-Canvas Menu */}
        {isMenuOpen && (
          <div className="fixed inset-0 z-50 bg-sidebar dark:bg-sidebar md:hidden">
            <div className="container mx-auto px-4 py-16 flex flex-col items-center gap-6">
              <button
                onClick={() => setIsMenuOpen(false)}
                className="absolute top-4 right-4 rounded-full w-10 h-10 flex items-center justify-center border border-sidebar-border transition-colors hover:bg-sidebar-primary/20"
                aria-label="Close menu"
              >
                <XMarkIcon className="h-5 w-5" />
              </button>
              
              {["home", "about", "services"].map((item) => (
                <Link 
                  key={item}
                  href="#" 
                  className="text-base font-medium text-sidebar-foreground hover:text-sidebar-accent-foreground transition-colors duration-300 px-6 py-2 rounded-full hover:bg-sidebar-primary/20 dark:hover:bg-sidebar-primary/30"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t(`nav.${item}`) || item.charAt(0).toUpperCase() + item.slice(1)}
                </Link>
              ))}
              
              {!isSignedIn && (
                <div className="flex flex-col items-center gap-4 mt-4">
                  <SignInButton>
                    <Link 
                      href="/sign-in" 
                      className="rounded-md bg-sidebar-primary px-6 py-2 text-base text-sidebar-primary-foreground transition-colors duration-300 hover:bg-sidebar-primary/90 dark:text-white"
                      onClick={() => setIsMenuOpen(false)}
                    >
                     {t("nav.signIn") || "Se Connecter"}
                    </Link>
                  </SignInButton>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </header>
  );
}