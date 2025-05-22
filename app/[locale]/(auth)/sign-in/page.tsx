"use client";

import React, { useState, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { useForm, SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useI18n } from "@/locales/client";
import { useCurrentLocale } from "@/locales/client";
import Link from "next/link";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { toast } from "sonner";
import { Navbar } from "@/components/shared/NavBar";
import { signIn } from "next-auth/react";
import { Eye, EyeOff, Loader2 } from "lucide-react";

const signInSchema = z.object({
  email: z.string().email({ message: "signIn.emailInvalid" }),
  password: z.string().min(1, { message: "signIn.passwordRequired" }),
  rememberMe: z.boolean().optional(),
});

type SignInFormData = z.infer<typeof signInSchema>;

export default function SignInPage() {
  const t = useI18n();
  const locale = useCurrentLocale();
  const router = useRouter();
  const searchParams = useSearchParams();
  const [isPageLoading, setIsPageLoading] = useState(true);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const callbackUrl = searchParams.get('callbackUrl') || `/${locale}/dashboard`;
  const [isClient, setIsClient] = useState(false);

  // Check for existing session on component mount
  useEffect(() => {
    setIsClient(true);
    const checkSession = async () => {
      try {
        console.log('🔍 Checking for existing session...');
        const response = await fetch('/api/auth/session');
        const data = await response.json();
        console.log('🔍 Session check response:', data);
        
        if (data?.user) {
          console.log('✅ User already signed in, redirecting to:', callbackUrl);
          router.push(callbackUrl);
          return;
        }
      } catch (error) {
        console.error('❌ Session check error:', error);
        toast.error(t('common.errorAlertTitle'), {
          description: t('signIn.sessionCheckFailed')
        });
      } finally {
        setIsPageLoading(false);
      }
    };

    checkSession();
  }, [router, callbackUrl, t]);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<SignInFormData>({
    resolver: zodResolver(signInSchema),
    defaultValues: {
      email: "",
      password: "",
      rememberMe: false,
    },
  });

  const onSubmit: SubmitHandler<SignInFormData> = async (data, event) => {
    event?.preventDefault();
    if (isSubmitting) return;
    
    console.log('🔑 Sign in attempt:', { email: data.email });
    setIsSubmitting(true);
    
    try {
      console.log('🔍 Calling signIn with credentials...');
      const result = await signIn('credentials', {
        redirect: false,
        email: data.email,
        password: data.password,
        rememberMe: data.rememberMe,
        callbackUrl: callbackUrl,
      });

      console.log('🔍 SignIn result:', result);

      if (!result) {
        console.error('❌ No response from server');
        throw new Error('No response from server');
      }

      if (result.error) {
        console.log('⚠️ Authentication error:', result.error);
        switch (result.error) {
          case 'EmailNotVerified':
            console.log('📧 Email not verified, generating OTP...');
            try {
              const otpResponse = await fetch('/api/auth/generate-otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: data.email }),
              });
              
              console.log('📩 OTP generation response:', {
                status: otpResponse.status,
                statusText: otpResponse.statusText
              });

              if (!otpResponse.ok) {
                const errorData = await otpResponse.json().catch(() => ({}));
                console.error('❌ Failed to send OTP:', errorData);
                throw new Error('Failed to send OTP');
              }
              
              console.log('🔄 Redirecting to OTP verification...');
              router.push(`/${locale}/otp-verification?email=${encodeURIComponent(data.email)}&callbackUrl=${encodeURIComponent(callbackUrl)}`);
              return;
            } catch (otpError) {
              console.error('❌ OTP generation error:', otpError);
              throw new Error('Failed to process OTP request');
            }
            
          case 'InvalidCredentials':
            console.log('❌ Invalid credentials provided');
            throw new Error(t('signIn.invalidCredentials'));
            
          case 'AccountRejected':
            console.log('❌ Account is rejected');
            throw new Error(t('signIn.accountRejected'));
            
          default:
            console.log('❌ Unknown authentication error:', result.error);
            throw new Error(t('signIn.errorGeneric'));
        }
      }
      
      console.log('✅ Authentication successful, redirecting to:', callbackUrl);
      toast.success(t("signIn.successMessage"));
      router.push(callbackUrl);
    } catch (error: any) {
      console.error('❌ Sign in error:', error);
      toast.error(error.message || t("signIn.errorGeneric"));
    } finally {
      setIsSubmitting(false);
    }
  };

  // Loading state
  if (isPageLoading || !isClient) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <div className="flex flex-col items-center space-y-4">
          <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
          <p className="text-muted-foreground">{t("common.loading")}</p>
        </div>
      </div>
    );
  }

  // Prevent form submission on Enter key in the form
  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      handleSubmit(onSubmit)(e);
    }
  };

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <div className="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 mt-20 mb-20">
        <div className="w-full max-w-md p-6 sm:p-8 space-y-6 bg-card shadow-xl rounded-lg">
          <div className="text-center space-y-2">
            <Image 
              src="/icon_blue.svg" 
              alt={t("app.logoAlt")} 
              width={60} 
              height={30} 
              className="mx-auto" 
            />
            <h1 className="text-3xl font-bold text-foreground">
              {t("signIn.title")}
            </h1>
            <p className="text-sm text-muted-foreground">
              {t("signIn.subtitle")}
            </p>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} onKeyDown={handleKeyDown} className="space-y-6">
            <div>
              <Label htmlFor="email" className="block text-sm font-medium text-foreground mb-1">
                {t("signIn.emailLabel")}
              </Label>
              <Input
                id="email"
                type="email"
                autoComplete="email"
                placeholder={t("signIn.emailPlaceholder")}
                className="w-full"
                {...register("email")}
              />
              {errors.email && (
                <p className="mt-1 text-sm text-destructive">
                  {t(errors.email.message as any, {})}
                </p>
              )}
            </div>

            <div>
              <div className="flex items-center justify-between mb-1">
                <Label htmlFor="password" className="text-sm font-medium text-foreground">
                  {t("signIn.passwordLabel")}
                </Label>
                <Link
                  href={`/${locale}/forgot-password`}
                  className="text-sm font-medium text-primary hover:text-primary/80"
                >
                  {t("signIn.forgotPassword")}
                </Link>
              </div>
              <div className="relative">
                <Input
                  id="password"
                  type={showPassword ? "text" : "password"}
                  autoComplete="current-password"
                  placeholder={t("signIn.passwordPlaceholder")}
                  className="w-full pr-10"
                  {...register("password")}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground"
                  aria-label={showPassword ? t("common.hidePassword") : t("common.showPassword")}
                >
                  {showPassword ? (
                    <EyeOff className="h-4 w-4" />
                  ) : (
                    <Eye className="h-4 w-4" />
                  )}
                </button>
              </div>
              {errors.password && (
                <p className="mt-1 text-sm text-destructive">
                  {t(errors.password.message as any, {})}
                </p>
              )}
            </div>

            <div className="flex items-center space-x-2">
              <Checkbox
                id="remember-me"
                {...register("rememberMe")}
              />
              <Label
                htmlFor="remember-me"
                className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
              >
                {t("signIn.rememberMe")}
              </Label>
            </div>

            <Button 
              type="submit" 
              className="w-full text-white"
              disabled={isSubmitting}
            >
              {isSubmitting ? (
                <div className="flex items-center justify-center">
                  <Loader2 className="h-4 w-4 mr-2 animate-spin" />
                  {t("signIn.submitting")}
                </div>
              ) : (
                t("signIn.submitButton")
              )}
            </Button>
          </form>

          <div className="relative mt-6">        
            <div className="mt-6 text-center">
              <p className="text-sm text-muted-foreground mb-4">
                {t("signIn.noAccount")}{' '}
                <Link 
                  href={`/${locale}/sign-up`} 
                  className="font-medium text-primary hover:text-primary/80 transition-colors"
                >
                  {t("signIn.signUpLink")}
                </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}