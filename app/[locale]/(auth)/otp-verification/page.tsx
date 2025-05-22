"use client";

import { useState, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useI18n } from "@/locales/client";
import { useCurrentLocale } from "@/locales/client";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { Navbar } from "@/components/shared/NavBar";

const otpSchema = z.object({
  code: z.string().min(6, { message: "otp.errorInvalidCode" }),
});

type OtpFormData = z.infer<typeof otpSchema>;

export default function OtpVerificationPage() {
  const t = useI18n();
  const locale = useCurrentLocale();
  const router = useRouter();
  const searchParams = useSearchParams();
  const [isLoading, setIsLoading] = useState(false);
  const [resendCooldown, setResendCooldown] = useState(60);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<OtpFormData>({
    resolver: zodResolver(otpSchema),
    defaultValues: {
      code: "",
    },
  });

  useEffect(() => {
    if (resendCooldown > 0) {
      const timer = setTimeout(() => setResendCooldown(resendCooldown - 1), 1000);
      return () => clearTimeout(timer);
    }
  }, [resendCooldown]);

  const onSubmit = async (data: OtpFormData) => {
    setIsLoading(true);
    try {
      // TODO: Implement actual OTP verification
      console.log("Verification code:", data.code);
      await new Promise((resolve) => setTimeout(resolve, 1000));
      
      toast.success(t("otp.successMessage"));
      router.push(`/${locale}/dashboard`);
    } catch (error) {
      console.error("Verification error:", error);
      toast.error(t("otp.errorInvalidCode"));
    } finally {
      setIsLoading(false);
    }
  };

  const handleResendCode = async () => {
    if (resendCooldown > 0) return;

    try {
      // TODO: Implement resend OTP logic
      console.log("Resending OTP...");
      await new Promise((resolve) => setTimeout(resolve, 1000));
      
      setResendCooldown(60);
      toast.success(t("otp.resendSuccess"));
    } catch (error) {
      console.error("Resend error:", error);
      toast.error(t("common.errorAlertTitle"));
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
              {t("otp.title")}
            </h1>
            <p className="text-sm text-muted-foreground">
              {t("otp.subtitle")}
            </p>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div>
              <Label htmlFor="code" className="block text-sm font-medium text-foreground mb-1">
                {t("otp.codeLabel")}
              </Label>
              <Input
                id="code"
                type="text"
                inputMode="numeric"
                autoComplete="one-time-code"
                placeholder={t("otp.codePlaceholder")}
                className="w-full text-center text-xl tracking-widest font-mono h-14"
                maxLength={6}
                {...register("code")}
              />
              {errors.code && (
                <p className="mt-1 text-sm text-red-500">
                  {t(errors.code.message as any, {})}
                </p>
              )}
            </div>

            <Button
              type="submit"
              className="w-full"
              disabled={isLoading}
            >
              {isLoading ? (
                <span className="flex items-center">
                  <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {t("common.verifying")}
                </span>
              ) : (
                t("otp.verifyButton")
              )}
            </Button>

            <div className="text-center text-sm">
              <p className="text-muted-foreground">
                {t("otp.didntReceiveCode")}{" "}
                <button
                  type="button"
                  onClick={handleResendCode}
                  disabled={resendCooldown > 0}
                  className={`font-medium ${
                    resendCooldown > 0
                      ? "text-muted-foreground cursor-not-allowed"
                      : "text-primary hover:underline"
                  }`}
                >
                  {resendCooldown > 0
                    ? `${t("otp.resendIn")} ${resendCooldown} ${t("otp.seconds")}`
                    : t("otp.resendCode")}
                </button>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}