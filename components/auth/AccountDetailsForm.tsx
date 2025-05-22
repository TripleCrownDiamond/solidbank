"use client";

import { UseFormRegister, Control, FieldErrors, UseFormWatch } from "react-hook-form";
import { Eye, EyeOff } from "lucide-react";
import { useState, useMemo } from "react";
import * as z from "zod";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Controller } from "react-hook-form";

// Assuming AccountDetailsSchema is defined in the parent and its type is passed
interface AccountDetailsFormValues {
  accountType: "CHECKING" | "SAVINGS";
  currency: "XOF" | "EUR" | "USD";
  phone?: string;
  email: string;
  password: string;
  identityDocumentUrl?: string;
  addressDocumentUrl?: string;
}

interface AccountDetailsFormProps {
  register: UseFormRegister<AccountDetailsFormValues>;
  control: Control<AccountDetailsFormValues>;
  errors: FieldErrors<AccountDetailsFormValues>;
  t: (key: string, params?: any) => string;
  handleFileChange: (event: React.ChangeEvent<HTMLInputElement>, type: 'identity' | 'address') => void;
  uploadedFileUrl: string | null;
  uploadedAddressFileUrl: string | null;
  watch: UseFormWatch<AccountDetailsFormValues>;
}

export const AccountDetailsForm: React.FC<AccountDetailsFormProps> = ({ 
  register, 
  control, 
  errors, 
  t, 
  handleFileChange, 
  uploadedFileUrl, 
  uploadedAddressFileUrl,
  watch 
}) => {
  const [showPassword, setShowPassword] = useState(false);
  const passwordValue = watch("password");

  const getPasswordStrengthInfo = (password: string | undefined) => {
    if (!password) return { strength: 0, messageKey: "", color: "bg-gray-200", strengthPercentage: 0, criteriaMet: [] as string[], criteriaMissing: [] as string[] };

    let score = 0;
    const criteriaMet: string[] = [];
    const criteriaMissing: string[] = [];
    const minLength = 6;

    if (password.length >= minLength) { score++; criteriaMet.push(t("signUp.passwordStrength.criteria.minLength", { count: minLength })); } else { criteriaMissing.push(t("signUp.passwordStrength.criteria.minLength", { count: minLength })); }
    if (/[A-Z]/.test(password)) { score++; criteriaMet.push(t("signUp.passwordStrength.criteria.uppercase")); } else { criteriaMissing.push(t("signUp.passwordStrength.criteria.uppercase")); }
    if (/[a-z]/.test(password)) { score++; criteriaMet.push(t("signUp.passwordStrength.criteria.lowercase")); } else { criteriaMissing.push(t("signUp.passwordStrength.criteria.lowercase")); }
    if (/[0-9]/.test(password)) { score++; criteriaMet.push(t("signUp.passwordStrength.criteria.number")); } else { criteriaMissing.push(t("signUp.passwordStrength.criteria.number")); }
    if (/[^A-Za-z0-9]/.test(password)) { score++; criteriaMet.push(t("signUp.passwordStrength.criteria.symbol")); } else { criteriaMissing.push(t("signUp.passwordStrength.criteria.symbol")); }

    let messageKey = "";
    let color = "bg-gray-200";
    const strengthPercentage = password.length === 0 ? 0 : Math.min(100, (score / 5) * 100);

    if (password.length > 0 && password.length < minLength) {
      messageKey = "signUp.passwordStrength.tooShort";
      color = "bg-red-500";
    } else if (password.length >= minLength) {
      if (score <= 2) { messageKey = "signUp.passwordStrength.weak"; color = "bg-red-500"; }
      else if (score === 3) { messageKey = "signUp.passwordStrength.medium"; color = "bg-yellow-500"; }
      else if (score === 4) { messageKey = "signUp.passwordStrength.strong"; color = "bg-blue-500"; }
      else { messageKey = "signUp.passwordStrength.veryStrong"; color = "bg-green-500"; }
    }
    return { strength: score, messageKey, color, strengthPercentage, criteriaMet, criteriaMissing };
  };

  const passwordStrengthInfo = useMemo(() => getPasswordStrengthInfo(passwordValue), [passwordValue, t]);

  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold text-center">{t("signUp.steps.step3Title")}</h2>
      {/* Account Type */}
      <div className="space-y-2">
        <Label htmlFor="accountType">{t("signUp.fields.accountType")}</Label>
          <Controller
            name="accountType"
            control={control}
            render={({ field }) => (
                <Select onValueChange={field.onChange} defaultValue={field.value as string | undefined}>
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder={t("signUp.placeholders.accountType")} />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="CHECKING">{t("signUp.accountTypes.checking")}</SelectItem>
                        <SelectItem value="SAVINGS">{t("signUp.accountTypes.savings")}</SelectItem>
                    </SelectContent>
                </Select>
            )}
        />
        {errors.accountType && <p className="text-sm text-destructive">{t(errors.accountType.message as any, {})}</p>}
      </div>

      {/* Currency */}
      <div className="space-y-2">
        <Label htmlFor="currency">{t("signUp.fields.currency")}</Label>
        <Controller
            name="currency"
            control={control}
            render={({ field }) => (
                <Select onValueChange={field.onChange} defaultValue={field.value as string | undefined}>
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder={t("signUp.placeholders.currency")} />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="EUR">{t("signUp.currencies.eur")}</SelectItem>
                        <SelectItem value="USD">{t("signUp.currencies.usd")}</SelectItem>
                    </SelectContent>
                </Select>
            )}
        />
        {errors.currency && <p className="text-sm text-destructive">{t(errors.currency.message as any, {})}</p>}
      </div>

      {/* Email */}
      <div className="space-y-2">
        <Label htmlFor="email">{t("signUp.fields.email")}</Label>
        <Input id="email" type="email" {...register("email")} placeholder={t("signUp.placeholders.email")} />
        {errors.email && <p className="text-sm text-destructive">{t(errors.email.message as any, {})}</p>}
      </div>

      {/* Password */}
      <div className="space-y-2">
        <Label htmlFor="password">{t("signUp.fields.password")}</Label>
        <div className="relative">
          <Input
            id="password"
            type={showPassword ? "text" : "password"}
            {...register("password")}
            placeholder={t("signUp.placeholders.password")}
            className="pr-10" // Add padding for the icon
          />
          <button
            type="button"
            onClick={() => setShowPassword(!showPassword)}
            className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:outline-none"
            aria-label={showPassword ? t("common.hidePassword") : t("common.showPassword")}
          >
            {showPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
          </button>
        </div>
        {errors.password && <p className="text-sm text-destructive">{t(errors.password.message as any, {})}</p>}
        
        {/* Password Strength Indicator */}
        {passwordValue && passwordValue.length > 0 && (
          <div className="mt-2 space-y-1">
            <div className="flex justify-between items-center mb-1">
              <p className="text-sm font-medium text-gray-700 dark:text-gray-300">{t("signUp.passwordStrength.title")}</p>
              {passwordStrengthInfo.messageKey && (
                <p className={`text-sm font-semibold ${
                  passwordStrengthInfo.color === 'bg-red-500' ? 'text-red-600' :
                  passwordStrengthInfo.color === 'bg-yellow-500' ? 'text-yellow-600' :
                  passwordStrengthInfo.color === 'bg-blue-500' ? 'text-blue-600' :
                  passwordStrengthInfo.color === 'bg-green-500' ? 'text-green-600' : 'text-gray-600'
                }`}>
                  {t(passwordStrengthInfo.messageKey)}
                </p>
              )}
            </div>
            <div className="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
              <div
                className={`h-2 rounded-full transition-all duration-300 ease-in-out ${passwordStrengthInfo.color}`}
                style={{ width: `${passwordStrengthInfo.strengthPercentage}%` }}
              ></div>
            </div>
            {passwordStrengthInfo.criteriaMissing.length > 0 && passwordStrengthInfo.strength < 5 && (
                 <ul className="list-disc list-inside text-xs text-gray-500 dark:text-gray-400 mt-1 space-y-0.5">
                    {passwordStrengthInfo.criteriaMissing.map((criterion, index) => (
                        <li key={`missing-${index}`}>{criterion}</li>
                    ))}
                </ul>
            )}
          </div>
        )}
      </div>

      {/* Identity Document Upload */}
      <div className="space-y-2">
        <Label htmlFor="identityDocument">{t("signUp.fields.identityDocument")}</Label>
        <Input 
          id="identityDocument" 
          type="file" 
          onChange={(e) => handleFileChange(e, 'identity')} 
          accept="image/*,.pdf" 
          className="cursor-pointer"
        />
        {uploadedFileUrl && (
          <p className="text-sm text-green-600">
            {t("signUp.fileUploaded")}:{" "}
            <a 
              href={uploadedFileUrl} 
              target="_blank" 
              rel="noopener noreferrer" 
              className="underline hover:text-green-700"
            >
              {uploadedFileUrl.split('/').pop()}
            </a>
          </p>
        )}
        {errors.identityDocumentUrl && (
          <p className="text-sm text-destructive">
            {t(errors.identityDocumentUrl.message as any, {})}
          </p>
        )}
      </div>

      {/* Address Document Upload */}
      <div className="space-y-2">
        <Label htmlFor="addressDocument">{t("signUp.fields.addressDocument")}</Label>
        <Input 
          id="addressDocument" 
          type="file" 
          onChange={(e) => handleFileChange(e, 'address')} 
          accept="image/*,.pdf" 
          className="cursor-pointer"
        />
        {uploadedAddressFileUrl && (
          <p className="text-sm text-green-600">
            {t("signUp.fileUploaded")}:{" "}
            <a 
              href={uploadedAddressFileUrl} 
              target="_blank" 
              rel="noopener noreferrer" 
              className="underline hover:text-green-700"
            >
              {uploadedAddressFileUrl.split('/').pop()}
            </a>
          </p>
        )}
        {errors.addressDocumentUrl && (
          <p className="text-sm text-destructive">
            {t(errors.addressDocumentUrl?.message as any, {})}
          </p>
        )}
      </div>
    </div>
  );
};
