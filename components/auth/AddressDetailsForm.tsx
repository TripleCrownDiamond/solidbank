// components/auth/AddressDetailsForm.tsx
"use client";

import React from "react";
import { UseFormRegister, FieldErrors, Controller, UseFormWatch, Control } from "react-hook-form";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { countries, getRegionsByCountryCode, Region, getPhoneCodeByCountryCode } from "@/lib/countries"; // Ensure all are imported

// Updated form values interface - Ensure this matches your Zod schema in the parent
export interface AddressDetailsFormValues {
  address: string;
  country: string; // Stores country CODE (e.g., "US", "FR")
  city: string;
  postalCode: string;
  region?: string; // Optional: region/state code (e.g., "CA", "NY")
  phoneNumber?: string; // The actual phone number part
  // Note: phoneCode is handled implicitly by the selected country or a separate input if needed
}

interface AddressDetailsFormProps {
  register: UseFormRegister<AddressDetailsFormValues>;
  errors: FieldErrors<AddressDetailsFormValues>;
  control: Control<AddressDetailsFormValues>; // Use the specific Control type
  watch: UseFormWatch<AddressDetailsFormValues>;
  setValue: (name: keyof AddressDetailsFormValues, value: any, options?: Object) => void; // For setting phone code
  t: (key: string, params?: any) => string;
}

export const AddressDetailsForm: React.FC<AddressDetailsFormProps> = ({
  register,
  errors,
  control,
  watch,
  setValue,
  t,
}) => {
  const countriesForSelect = countries.map(c => ({ value: c.code, label: c.name, phoneCode: c.phoneCode }));

  const selectedCountryCode = watch("country");
  const [regionsForSelectedCountry, setRegionsForSelectedCountry] = React.useState<Region[]>([]);
  const [currentPhoneCode, setCurrentPhoneCode] = React.useState<string | undefined>("");

  React.useEffect(() => {
    if (selectedCountryCode) {
      const regions = getRegionsByCountryCode(selectedCountryCode);
      setRegionsForSelectedCountry(regions || []);
      const phoneCode = getPhoneCodeByCountryCode(selectedCountryCode);
      setCurrentPhoneCode(phoneCode);
      // Automatically update a hidden phoneCode field if you add one, or use currentPhoneCode directly
    } else {
      setRegionsForSelectedCountry([]);
      setCurrentPhoneCode("");
    }
  }, [selectedCountryCode, setValue]);

  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold text-center">{t("signUp.steps.step2Title")}</h2>

      {/* Address */}
      <div className="space-y-2">
        <Label htmlFor="address">{t("signUp.fields.address")}</Label>
        <Input id="address" {...register("address")} placeholder={t("signUp.placeholders.address")} />
        {errors.address && <p className="text-sm text-destructive">{errors.address.message}</p>}
      </div>

      {/* Country Select */}
      <div className="space-y-2">
        <Label htmlFor="country">{t("signUp.fields.country")}</Label>
        <Controller
          name="country"
          control={control}
          render={({ field }) => (
            <Select 
              onValueChange={(value) => {
                field.onChange(value);
                // Optionally clear region if country changes and new country doesn't have the old region
                setValue("region", "", { shouldValidate: true }); 
              }} 
              defaultValue={field.value}
            >
              <SelectTrigger id="country" className="w-full">
                <SelectValue placeholder={t("signUp.placeholders.selectCountry", { defaultValue: "Select a country..." })} />
              </SelectTrigger>
              <SelectContent>
                {countriesForSelect.map((country) => (
                  <SelectItem key={country.value} value={country.value}>
                    {country.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          )}
        />
        {errors.country && <p className="text-sm text-destructive">{errors.country.message}</p>}
      </div>
      
      {/* Region Field (Conditional based on country) */}
      {regionsForSelectedCountry.length > 0 ? (
         <div className="space-y-2">
           <Label htmlFor="region">{t("signUp.fields.region", { defaultValue: "Region/State" })}</Label>
           <Controller
             name="region" // Make sure 'region' is in AddressDetailsFormValues & Zod schema
             control={control}
             render={({ field }) => (
               <Select onValueChange={field.onChange} defaultValue={field.value}>
                 <SelectTrigger id="region" className="w-full">
                   <SelectValue placeholder={t("signUp.placeholders.selectRegion", { defaultValue: "Select a region..." })} />
                 </SelectTrigger>
                 <SelectContent>
                   {regionsForSelectedCountry.map((region) => (
                     <SelectItem key={region.code} value={region.code}>
                       {region.name}
                     </SelectItem>
                   ))}
                 </SelectContent>
               </Select>
             )}
           />
           {errors.region && <p className="text-sm text-destructive">{errors.region.message}</p>}
         </div>
      ) : (
        <div className="space-y-2">
          <Label htmlFor="region">{t("signUp.fields.regionOptional", { defaultValue: "Region/State (Optional)"})}</Label>
          <Input id="region" {...register("region")} placeholder={t("signUp.placeholders.region", { defaultValue: "Enter region/state" })} />
          {errors.region && <p className="text-sm text-destructive">{errors.region.message}</p>}
        </div>
      )}

      {/* City */}
      <div className="space-y-2">
        <Label htmlFor="city">{t("signUp.fields.city")}</Label>
        <Input id="city" {...register("city")} placeholder={t("signUp.placeholders.city")} />
        {errors.city && <p className="text-sm text-destructive">{errors.city.message}</p>}
      </div>

      {/* Postal Code */}
      <div className="space-y-2">
        <Label htmlFor="postalCode">{t("signUp.fields.postalCode")}</Label>
        <Input id="postalCode" {...register("postalCode")} placeholder={t("signUp.placeholders.postalCode")} />
        {errors.postalCode && <p className="text-sm text-destructive">{errors.postalCode.message}</p>}
      </div>

      {/* Phone Number */}
      <div className="space-y-2">
        <Label htmlFor="phoneNumber">{t("signUp.fields.phoneNumber", { defaultValue: "Phone Number" })}</Label>
        <div className="flex items-center gap-2">
          {currentPhoneCode && (
            <div className="w-auto px-3 py-2 text-sm border rounded-md bg-muted">
              {currentPhoneCode}
            </div>
          )}
          <Input
            id="phoneNumber" // Make sure 'phoneNumber' is in AddressDetailsFormValues & Zod schema
            type="tel"
            className="flex-1"
            {...register("phoneNumber")}
            placeholder={t("signUp.placeholders.phoneNumber", { defaultValue: "Enter phone number" })}
          />
        </div>
        {errors.phoneNumber && <p className="text-sm text-destructive mt-1">{errors.phoneNumber.message}</p>}
      </div>
    </div>
  );
};