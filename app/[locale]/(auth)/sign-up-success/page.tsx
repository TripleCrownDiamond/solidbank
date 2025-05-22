"use client";

import { useEffect, useState, useCallback } from 'react';
import { useRouter } from 'next/navigation';
import { useI18n } from '@/locales/client';
import { Button } from '@/components/ui/button';
import { CheckCircle2 } from 'lucide-react';
import Link from 'next/link';

export default function SignUpSuccessPage() {
  const router = useRouter();
  const t = useI18n();
  const [countdown, setCountdown] = useState(5); // Reduced from 60 to 5 for better UX
  const [isNavigating, setIsNavigating] = useState(false);

  // Get the current locale safely
  const getCurrentLocale = useCallback(() => {
    if (typeof window === 'undefined') return 'en';
    const pathParts = window.location.pathname.split('/');
    return pathParts[1] || 'en';
  }, []);

  // Handle manual navigation
  const handleNavigateToSignIn = useCallback(() => {
    if (isNavigating) return;
    setIsNavigating(true);
    const locale = getCurrentLocale();
    router.push(`/${locale}/sign-in`);
  }, [isNavigating, router, getCurrentLocale]);

  useEffect(() => {
    // Only run on client side
    if (typeof window === 'undefined') return;

    const timer = setInterval(() => {
      setCountdown((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          handleNavigateToSignIn();
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(timer);
  }, [handleNavigateToSignIn]);

  // Get the current locale for the Link component
  const currentLocale = getCurrentLocale();

  return (
    <div className="flex min-h-screen flex-col items-center justify-center bg-background p-4">
      <div className="w-full max-w-md space-y-6 rounded-lg bg-card p-8 text-center shadow-md">
        <div className="flex justify-center">
          <div className="rounded-full bg-green-100 p-4">
            <CheckCircle2 className="h-12 w-12 text-green-600" />
          </div>
        </div>
        
        <h1 className="text-2xl font-bold tracking-tight">
          {t('signUp.successTitle')}
        </h1>
        
        <p className="text-muted-foreground">
          {t('signUp.successMessage')}
        </p>
        
        <p className="text-sm text-muted-foreground">
          {t('signUp.redirectMessage')} {countdown} {t('common.seconds')}
        </p>
        
        <div className="pt-4">
          <Button 
            onClick={handleNavigateToSignIn}
            className="w-full text-white hover:text-white dark:text-white"
            disabled={isNavigating}
          >
            {isNavigating ? t('common.loading') : t('signUp.goToSignIn')}
          </Button>
        </div>
      </div>
    </div>
  );
}
