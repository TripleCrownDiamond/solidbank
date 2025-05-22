"use client";

import { UseFormRegister, Control, FieldErrors } from "react-hook-form";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Calendar } from "@/components/ui/calendar";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import React from "react";
import { Controller } from "react-hook-form";
import { cn } from "@/lib/utils";
import { format } from "date-fns";
import { CalendarIcon } from "lucide-react";

interface PersonalDetailsFormValues {
  firstName: string;
  lastName: string;
  birthDate: Date;
  gender: "MALE" | "FEMALE" | "OTHER";
  maritalStatus?: string;
  profession?: string;
}

interface PersonalDetailsFormProps {
  register: UseFormRegister<PersonalDetailsFormValues>;
  control: Control<PersonalDetailsFormValues>;
  errors: FieldErrors<PersonalDetailsFormValues>;
  t: (key: string, params?: any) => string;
}

export const PersonalDetailsForm: React.FC<PersonalDetailsFormProps> = ({ register, control, errors, t }) => {
  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold text-center">{t("signUp.steps.step1Title")}</h2>
      {/* First Name */}
      <div className="space-y-2">
        <Label htmlFor="firstName">{t("signUp.fields.firstName")}</Label>
        <Input id="firstName" {...register("firstName")} placeholder={t("signUp.placeholders.firstName")} />
        {errors.firstName && <p className="text-sm text-destructive">{t(errors.firstName.message as any, {})}</p>}
      </div>

      {/* Last Name */}
      <div className="space-y-2">
        <Label htmlFor="lastName">{t("signUp.fields.lastName")}</Label>
        <Input id="lastName" {...register("lastName")} placeholder={t("signUp.placeholders.lastName")} />
        {errors.lastName && <p className="text-sm text-destructive">{t(errors.lastName.message as any, {})}</p>}
      </div>
      
      {/* Birth Date */}
      <div className="space-y-2">
        <Label htmlFor="birthDate">{t("signUp.fields.birthDate")}</Label>
        <Controller
          name="birthDate"
          control={control}
          render={({ field }) => {
            const [calendarViewDate, setCalendarViewDate] = React.useState<Date>(field.value || new Date(new Date().getFullYear(), 0, 1));
            const fromYearVal = 1900;
            const toYearVal = new Date().getFullYear();
            const years = Array.from({ length: toYearVal - fromYearVal + 1 }, (_, i) => toYearVal - i);

            React.useEffect(() => {
              if (field.value) {
                // Update calendar view if form value changes and is different from current view
                if (field.value.getFullYear() !== calendarViewDate.getFullYear() || field.value.getMonth() !== calendarViewDate.getMonth()) {
                  setCalendarViewDate(field.value);
                }
              } else {
                // If field is cleared, reset view to a sensible default (e.g. Jan 1st of current year)
                setCalendarViewDate(new Date(toYearVal, 0, 1));
              }
            // eslint-disable-next-line react-hooks/exhaustive-deps
            }, [field.value]);

            return (
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant={"outline"}
                  className={cn(
                    "w-full justify-start text-left font-normal",
                    !field.value && "text-muted-foreground"
                  )}
                >
                  <CalendarIcon className="mr-2 h-4 w-4" />
                  {field.value ? format(field.value, "PPP") : <span>{t("signUp.placeholders.birthDate")}</span>}
                </Button>
              </PopoverTrigger>
              <PopoverContent align="start" className="flex w-auto flex-col space-y-2 p-2">
                <Select
                  value={calendarViewDate.getFullYear().toString()}
                  onValueChange={(yearValue) => {
                    const selectedYear = parseInt(yearValue);
                    const currentMonth = calendarViewDate.getMonth();
                    const dayInField = field.value ? field.value.getDate() : 1; // Try to keep current day
                    // Check if this day is valid for the new month/year
                    const daysInSelectedMonth = new Date(selectedYear, currentMonth + 1, 0).getDate();
                    const dayToSet = Math.min(dayInField, daysInSelectedMonth);

                    const newDate = new Date(selectedYear, currentMonth, dayToSet);
                    field.onChange(newDate); // Update form field
                    setCalendarViewDate(newDate); // Update calendar's month/year view
                  }}
                >
                  <SelectTrigger>
                    <SelectValue placeholder={t("signUp.placeholders.selectYear")} />
                  </SelectTrigger>
                  <SelectContent position="popper">
                    {years.map((year) => (
                      <SelectItem key={year} value={year.toString()}>
                        {year}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                <div className="rounded-md border">
                  <Calendar
                    mode="single"
                    selected={field.value}
                    onSelect={(date) => {
                      field.onChange(date);
                      if (date) {
                        setCalendarViewDate(date); // Sync calendar view on day select
                      }
                    }}
                    month={calendarViewDate} // Controlled month/year
                    onMonthChange={setCalendarViewDate} // Sync when user navigates calendar months
                    fromYear={fromYearVal}
                    toYear={toYearVal}
                  />
                </div>
              </PopoverContent>
            </Popover>
          )}}
        />
        {errors.birthDate && <p className="text-sm text-destructive">{t(errors.birthDate.message as any, {})}</p>}
      </div>

      {/* Gender */}
      <div className="space-y-2">
        <Label htmlFor="gender">{t("signUp.fields.gender")}</Label>
          <Controller
            name="gender"
            control={control}
            render={({ field }) => (
                <Select onValueChange={field.onChange} defaultValue={field.value as string | undefined}>
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder={t("signUp.placeholders.gender")} />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="MALE">{t("signUp.genders.male")}</SelectItem>
                        <SelectItem value="FEMALE">{t("signUp.genders.female")}</SelectItem>
                        <SelectItem value="OTHER">{t("signUp.genders.other")}</SelectItem>
                    </SelectContent>
                </Select>
            )}
        />
        {errors.gender && <p className="text-sm text-destructive">{t(errors.gender.message as any, {})}</p>}
      </div>

      {/* Marital Status (Optional) */}
      <div className="space-y-2">
        <Label htmlFor="maritalStatus">{t("signUp.fields.maritalStatus")} ({t("signUp.optional")})</Label>
        <Input id="maritalStatus" {...register("maritalStatus")} placeholder={t("signUp.placeholders.maritalStatus")} />
      </div>

      {/* Profession (Optional) */}
      <div className="space-y-2">
        <Label htmlFor="profession">{t("signUp.fields.profession")} ({t("signUp.optional")})</Label>
        <Input id="profession" {...register("profession")} placeholder={t("signUp.placeholders.profession")} />
      </div>
    </div>
  );
};