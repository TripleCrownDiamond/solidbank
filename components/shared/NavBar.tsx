"use client";

import Link from "next/link";
import Image from "next/image";
import { useI18n } from "../../locales/client";
import { ThemeToggle } from "../theme-toggle";
import { useTheme } from "next-themes";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/outline";
import { useState } from "react";
import { usePathname, useParams } from 'next/navigation';
import { cn } from "@/lib/utils";

export function Navbar() {
  const t = useI18n();
  const { theme } = useTheme();
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const pathname = usePathname();
  const params = useParams();
  const locale = params?.locale as string || 'en';

  const isAuthPage = pathname.includes('/sign-in') || pathname.includes('/sign-up') || pathname.includes('/otp-verification') || pathname.includes('/forgot-password');
  
  const navItems = [
    { id: 'home', path: `/${locale}` },
    { id: 'about', path: `/${locale}/about` },
    { id: 'services', path: `/${locale}/services` },
  ];
  
  const isActive = (path: string) => {
    return pathname === path || 
           (path !== `/${locale}` && pathname.startsWith(path));
  };

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
        <nav className="hidden md:flex items-center gap-2">
          {navItems.map((item) => (
            <Link 
              key={item.id}
              href={item.path}
              className={cn(
                "text-sm px-4 py-2 rounded-full transition-colors duration-300",
                "text-sidebar-foreground hover:bg-sidebar-primary/10 hover:text-sidebar-primary",
                "dark:text-sidebar-foreground dark:hover:bg-sidebar-primary/20",
                isActive(item.path) 
                  ? "bg-sidebar-primary/10 text-sidebar-primary font-medium" 
                  : ""
              )}
            >
              {t(`nav.${item.id}` as any, {}) || item.id.charAt(0).toUpperCase() + item.id.slice(1)}
            </Link>
          ))}
        </nav>

        {/* Desktop Auth & Theme */}
        <div className="hidden md:flex items-center gap-4">
          {!isAuthPage && (
            <Link 
              href={`/${locale}/sign-in`} 
              className="rounded-md bg-sidebar-primary px-4 py-2 text-sm text-sidebar-primary-foreground transition-all duration-300 hover:bg-sidebar-primary/90 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sidebar-ring dark:bg-sidebar-primary dark:text-white active:scale-95"
            >
              {t("nav.signIn") || "Se Connecter"}
            </Link>
          )}
          <ThemeToggle />
        </div>

        {/* Mobile Controls */}
        <div className="flex md:hidden items-center gap-2">
          {/* User button will be added once new auth provider is integrated */}

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
          <div className="fixed inset-0 z-50 bg-background dark:bg-background md:hidden">
            <div className="container mx-auto px-4 py-16 flex flex-col items-center gap-6 bg-card dark:bg-card">
              <button
                onClick={() => setIsMenuOpen(false)}
                className="absolute top-4 right-4 rounded-full w-10 h-10 flex items-center justify-center border border-sidebar-border transition-colors hover:bg-sidebar-primary/20"
                aria-label="Close menu"
              >
                <XMarkIcon className="h-5 w-5" />
              </button>
              
              {navItems.map((item) => (
                <Link 
                  key={item.id}
                  href={item.path}
                  className={cn(
                    "text-base px-6 py-3 w-full text-center rounded-lg transition-colors duration-300",
                    "text-sidebar-foreground hover:bg-sidebar-primary/10 hover:text-sidebar-primary",
                    "dark:text-sidebar-foreground dark:hover:bg-sidebar-primary/20",
                    isActive(item.path) 
                      ? "bg-sidebar-primary/10 text-sidebar-primary font-medium" 
                      : ""
                  )}
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t(`nav.${item.id}` as any, {}) || item.id.charAt(0).toUpperCase() + item.id.slice(1)}
                </Link>
              ))}
              
              {/* Authentication status will be handled by a different provider */}
              {!isAuthPage && (
                <div className="flex flex-col items-center gap-4 mt-4">
                  <Link 
                    href={`/${locale}/sign-in`}
                    className="w-full rounded-lg bg-sidebar-primary px-6 py-3 text-base text-sidebar-primary-foreground transition-all duration-300 hover:bg-sidebar-primary/90 hover:shadow-md dark:text-white active:scale-95 text-center"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {t("nav.signIn") || "Se Connecter"}
                  </Link>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </header>
  );
}