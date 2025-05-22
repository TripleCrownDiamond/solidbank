"use client";

import React, { useState, useEffect, useMemo } from "react";
import Image from "next/image";
import { useRouter } from "next/navigation";
import { useForm, SubmitHandler, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import { useI18n } from "@/locales/client";
import { useCurrentLocale } from "@/locales/client";
import { Navbar } from "@/components/shared/NavBar";
import { toast } from "sonner";
import { PersonalDetailsForm } from "@/components/auth/PersonalDetailsForm";
import { AddressDetailsForm } from "@/components/auth/AddressDetailsForm";
import { AccountDetailsForm } from "@/components/auth/AccountDetailsForm";
import { Button } from "@/components/ui/button";
import { Progress } from "@/components/ui/progress";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { X } from "lucide-react";
import Link from "next/link";



const SIGN_UP_FORM_DATA_KEY = "signUpFormData";

const uploadToCloudinary = async (file: File): Promise<string> => {
  const formData = new FormData();
  formData.append("file", file);
  formData.append("upload_preset", "solidbank_unsigned_id_upload");
  const cloudName = "lucioletechnology";

  const response = await fetch(`https://api.cloudinary.com/v1_1/${cloudName}/image/upload`, {
    method: "POST",
    body: formData,
  });

  if (!response.ok) {
    const errorData = await response.json().catch(() => ({}));
    throw new Error(errorData.error?.message || "Failed to upload file");
  }

  const data = await response.json();
  return data.secure_url;
};


export default function SignUpPage() {
  const t = useI18n();
  const currentLocale = useCurrentLocale();
  const router = useRouter();

  // --- Zod Schemas (Defined inside component to access 't') ---
  const PersonalDetailsSchema = useMemo(() => z.object({
    firstName: z.string().min(1, { message: t("signUp.errors.firstNameRequired") }),
    lastName: z.string().min(1, { message: t("signUp.errors.lastNameRequired") }),
    birthDate: z.date({ required_error: t("signUp.errors.birthDateRequired") }),
    gender: z.enum(["MALE", "FEMALE", "OTHER"], {
        required_error: t("signUp.errors.genderRequired"),
        invalid_type_error: t("signUp.errors.genderInvalid"),
      }),
    maritalStatus: z.string().optional(),
    profession: z.string().optional(),
  }), [t]);
  type PersonalDetailsFormValues = z.infer<typeof PersonalDetailsSchema>;

  const AddressDetailsSchema = useMemo(() => z.object({
    address: z.string().min(1, { message: t("signUp.errors.addressRequired") }),
    country: z.string().min(1, { message: t("signUp.errors.countryRequired") }), // Expects country code
    city: z.string().min(1, { message: t("signUp.errors.cityRequired") }),
    postalCode: z.string().nonempty({ message: t("signUp.errors.postalCodeRequired") }),
    region: z.string().optional(), // Add region (optional for now)
    phoneNumber: z.string()
      .optional()
      .refine(val => !val || /^[\d\s()+-]*$/.test(val), { // Allows empty string or digits, spaces, parentheses, plus/minus
        message: t("signUp.errors.phoneNumberInvalid"),
      })
      .refine(val => !val || val.replace(/[^\d]/g, '').length >= 8, { // Ensure minimum 8 digits
        message: t("signUp.errors.phoneNumberMinLength"),
      }),
  }), [t]);
type AddressDetailsFormValues = z.infer<typeof AddressDetailsSchema>;

  const AccountDetailsSchema = useMemo(() => z.object({
    accountType: z.enum(["CHECKING", "SAVINGS"], { required_error: t("signUp.errors.accountTypeRequired") }),
    currency: z.enum(["XOF", "EUR", "USD"], { required_error: t("signUp.errors.currencyRequired") }),
    phone: z.string().optional(),
    email: z.string().email({ message: t("signUp.errors.emailInvalid") }).min(1, { message: t("signUp.errors.emailRequired") }),
    password: z.string().min(6, { message: t("signUp.errors.passwordMinLength", { count: 6 }) }),
    identityDocumentUrl: z.string().optional(),
    addressDocumentUrl: z.string().optional(),
  }), [t]);
type AccountDetailsFormValues = z.infer<typeof AccountDetailsSchema>;

  // Combined schema for type inference
  const FullSignUpSchema = useMemo(() => 
    PersonalDetailsSchema.merge(AddressDetailsSchema).merge(AccountDetailsSchema), 
    [PersonalDetailsSchema, AddressDetailsSchema, AccountDetailsSchema]);
  
  type FullSignUpFormValues = z.infer<typeof FullSignUpSchema>;

  // Type for localStorage data
  type StoredSignUpData = {
    step1Data?: PersonalDetailsFormValues;
    step2Data?: AddressDetailsFormValues;
    step3Data?: AccountDetailsFormValues;
    currentStep?: number;
    uploadedFileUrl?: string | null;
    uploadedAddressFileUrl?: string | null;
    formData?: Partial<FullSignUpFormValues>;
  };

  // Component state
  const [currentStep, setCurrentStep] = useState<number>(1);
  const [formData, setFormData] = useState<Partial<FullSignUpFormValues>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);

  const [uploadedFileUrl, setUploadedFileUrl] = useState<string | null>(null);
  const [uploadedAddressFileUrl, setUploadedAddressFileUrl] = useState<string | null>(null);

  const totalSteps = 3;

  // --- Form Hooks for each step ---
  const {
    register: registerStep1,
    handleSubmit: handleSubmitStep1,
    formState: { errors: errorsStep1, isValid: isValidStep1 },
    control: controlStep1, // For controlled components like Select, DatePicker
    trigger: triggerStep1,
    reset: resetStep1,
    watch: watchStep1,
  } = useForm<z.infer<typeof PersonalDetailsSchema>>({
    resolver: zodResolver(PersonalDetailsSchema),
    mode: "onChange", // Validate on change for better UX
  });

  const {
    register: registerStep2,
    handleSubmit: handleSubmitStep2,
    formState: { errors: errorsStep2, isValid: isValidStep2 },
    control: controlStep2,
    trigger: triggerStep2,
    watch: watchStep2,
    setValue: setValueStep2,
    reset: resetStep2,
  } = useForm<z.infer<typeof AddressDetailsSchema>>({
    resolver: zodResolver(AddressDetailsSchema),
    mode: "onChange",
  });

  const {
    register: registerStep3,
    handleSubmit: handleSubmitStep3,
    formState: { errors: errorsStep3, isValid: isValidStep3 },
    control: controlStep3,
    watch: watchStep3, // To watch file input
    setValue: setValueStep3, // To set identityDocumentUrl
    trigger: triggerStep3,
    reset: resetStep3,
  } = useForm<z.infer<typeof AccountDetailsSchema>>({
    resolver: zodResolver(AccountDetailsSchema),
    mode: "onChange",
  });

  const showToast = (type: 'success' | 'error', title: string, message: string) => {
    if (type === 'success') {
      toast.success(title, { description: message });
    } else {
      toast.error(title, { description: message });
    }
  };

  // Effect to load data from localStorage on component mount
  useEffect(() => {
    const loadSavedData = async () => {
      const savedDataJSON = localStorage.getItem(SIGN_UP_FORM_DATA_KEY);
      if (!savedDataJSON) return;

      try {
        const savedData = JSON.parse(savedDataJSON) as StoredSignUpData;
        if (savedData.step1Data) {
          const step1ToReset = { ...savedData.step1Data };
          if (step1ToReset.birthDate && typeof step1ToReset.birthDate === 'string') {
            step1ToReset.birthDate = new Date(step1ToReset.birthDate);
          }
          resetStep1(step1ToReset);
        }
        if (savedData.step2Data) resetStep2(savedData.step2Data);
        if (savedData.step3Data) resetStep3(savedData.step3Data);
        if (savedData.currentStep) setCurrentStep(savedData.currentStep);
        if (savedData.uploadedFileUrl !== undefined) setUploadedFileUrl(savedData.uploadedFileUrl);
        if (savedData.uploadedAddressFileUrl !== undefined) setUploadedAddressFileUrl(savedData.uploadedAddressFileUrl);
        if (savedData.formData) setFormData(savedData.formData);
      } catch {
        localStorage.removeItem(SIGN_UP_FORM_DATA_KEY);
      }
    };

    loadSavedData();
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Effect to save data to localStorage when form data or step changes
  const watchedStep1Data = watchStep1();
  const watchedStep2Data = watchStep2();
  const watchedStep3Data = watchStep3();

  useEffect(() => {
    const dataToStore: StoredSignUpData = {
      step1Data: watchedStep1Data,
      step2Data: watchedStep2Data,
      step3Data: watchedStep3Data,
      currentStep: currentStep,
      uploadedFileUrl: uploadedFileUrl,
      uploadedAddressFileUrl: uploadedAddressFileUrl,
      formData: formData,
    };
    if (Object.keys(watchedStep1Data).length > 0 || 
        Object.keys(watchedStep2Data).length > 0 || 
        Object.keys(watchedStep3Data).length > 0 || 
        currentStep > 1 || 
        uploadedFileUrl !== null ||
        uploadedAddressFileUrl !== null) {
      localStorage.setItem(SIGN_UP_FORM_DATA_KEY, JSON.stringify(dataToStore));
    }
  }, [watchedStep1Data, watchedStep2Data, watchedStep3Data, currentStep, uploadedFileUrl, uploadedAddressFileUrl, formData]);

  const nextStep = async () => {
    let isValid = false;
    if (currentStep === 1) {
      isValid = await triggerStep1(); // Manually trigger validation for all fields in step 1
      if (isValid) setCurrentStep((prev) => prev + 1);
    } else if (currentStep === 2) {
      isValid = await triggerStep2();
      if (isValid) setCurrentStep((prev) => prev + 1);
    }
    // No validation needed to move from step 3 to "next" as it will be "submit"
  };

  const prevStep = () => {
    setCurrentStep((prev) => prev - 1);
  };

  const onStep1Submit: SubmitHandler<z.infer<typeof PersonalDetailsSchema>> = (data) => {
    setFormData((prev) => ({ ...prev, ...data }));
    nextStep();
  };

  const onStep2Submit: SubmitHandler<z.infer<typeof AddressDetailsSchema>> = (data) => {
    setFormData((prev) => ({ ...prev, ...data }));
    nextStep();
  };

  const onFinalSubmit: SubmitHandler<z.infer<typeof AccountDetailsSchema>> = async (dataStep3) => {
    if (isSubmitting) return;
    
    setIsSubmitting(true);
    
    try {
      // Submit form data to your API
      const response = await fetch("/api/auth/sign-up", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ 
          ...formData, 
          ...dataStep3, 
          identityDocumentUrl: uploadedFileUrl,
          addressDocumentUrl: uploadedAddressFileUrl 
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        // Handle email already exists error specifically
        if (errorData.message && errorData.message.toLowerCase().includes('email') && 
            (errorData.message.toLowerCase().includes('already exists') || 
             errorData.message.toLowerCase().includes('déjà utilisé'))) {
          throw new Error("signUp.errors.emailAlreadyExists");
        }
        throw new Error(errorData.message || "signUp.errors.submissionFailed");
      }

      // Redirect to success page before resetting form state
      router.push(`/${currentLocale}/sign-up-success`);
      
      // Clear form data from local storage after redirect
      localStorage.removeItem(SIGN_UP_FORM_DATA_KEY);
      
      // Reset all form steps
      resetStep1();
      resetStep2();
      resetStep3();
      
      // Reset component state
      setFormData({});
      setUploadedFileUrl(null);
      setUploadedAddressFileUrl(null);
      setCurrentStep(1);
    } catch (error: any) {
      setIsSubmitted(false);
      const errorMessage = error.message && error.message.startsWith("signUp.errors.")
        ? t(error.message as any, {})
        : error.message || t("signUp.submissionError");
        
      showToast('error', t("common.errorAlertTitle"), errorMessage);
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleFileChange = async (event: React.ChangeEvent<HTMLInputElement>, type: 'identity' | 'address') => {
    const file = event.target.files?.[0];
    if (!file) return;
    
    setIsSubmitting(true);
    
    try {
      const fileUrl = await uploadToCloudinary(file);
      
      if (type === 'identity') {
        setValueStep3("identityDocumentUrl", fileUrl);
        setUploadedFileUrl(fileUrl);
      } else {
        setValueStep3("addressDocumentUrl", fileUrl);
        setUploadedAddressFileUrl(fileUrl);
      }
      
      showToast('success', t("common.success"), t("signUp.fileUploaded"));
    } catch (error: any) {
      const errorMessage = error.message || t("signUp.errors.fileUploadFailed");
      showToast('error', t("common.error"), errorMessage);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <main className="container mx-auto px-4 py-8">
        <div className="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 mt-20 mb-20">
          <div className="w-full max-w-2xl p-6 sm:p-8 space-y-6 bg-card shadow-xl rounded-lg">
            <div className="text-center space-y-2">
              <Image src="/icon_blue.svg" alt={t("app.logoAlt")} width={60} height={30} className="mx-auto" />
              <h1 className="text-3xl font-bold text-foreground">{t("signUp.title")}</h1>
            </div>

            <Progress value={(currentStep / totalSteps) * 100} className="w-full mb-6" />
            <p className="text-center text-sm text-muted-foreground">
              {t("signUp.step")} {currentStep} {t("signUp.of")} {totalSteps}
            </p>

            {/* --- Step 1: Personal Details --- */}
            {currentStep === 1 && (
              <form onSubmit={handleSubmitStep1(onStep1Submit)} className="space-y-6">
                <PersonalDetailsForm
                  register={registerStep1}
                  control={controlStep1}
                  errors={errorsStep1}
                  t={t}
                />
              </form>
            )}

            {/* --- Step 2: Address Details (Placeholder) --- */}
            {currentStep === 2 && (
              <form onSubmit={handleSubmitStep2(onStep2Submit)} className="space-y-6">
                <AddressDetailsForm
                  register={registerStep2}
                  errors={errorsStep2}
                  control={controlStep2} // Assuming controlStep2 exists from useForm for step 2
                  watch={watchStep2} // Assuming watchStep2 exists from useForm for step 2
                  setValue={setValueStep2} // Assuming setValueStep2 exists from useForm for step 2
                  t={t}
                />
              </form>
            )}

            {/* --- Step 3: Account Information (Placeholder) --- */}
            {currentStep === 3 && (
              <form onSubmit={handleSubmitStep3(onFinalSubmit)} className="space-y-6">
                <AccountDetailsForm
                  register={registerStep3}
                  control={controlStep3}
                  errors={errorsStep3}
                  t={t}
                  handleFileChange={handleFileChange}
                  uploadedFileUrl={uploadedFileUrl}
                  uploadedAddressFileUrl={uploadedAddressFileUrl}
                  watch={watchStep3}
                />
              </form>
            )}

            {/* Navigation Buttons */}
            <div className="flex flex-col pt-6 space-y-4">
              <div className="flex justify-between">
                {currentStep > 1 ? (
                  <Button type="button" variant="outline" onClick={prevStep} disabled={isSubmitting}>
                    {t("signUp.buttons.previous")}
                  </Button>
                ) : <div />}
                
                {currentStep < totalSteps ? (
                  <Button 
                    type="button" 
                    onClick={() => {
                      if (currentStep === 1) handleSubmitStep1(onStep1Submit)();
                      else if (currentStep === 2) handleSubmitStep2(onStep2Submit)();
                    }}
                    disabled={isSubmitting || (currentStep === 1 && !isValidStep1) || (currentStep === 2 && !isValidStep2)}
                    className="ml-auto text-white"
                  >
                    {t("signUp.buttons.next")}
                  </Button>
                ) : (
                  <Button 
                    type="button" 
                    onClick={handleSubmitStep3(onFinalSubmit)}
                    disabled={isSubmitting || !isValidStep3 || isSubmitted} 
                    className="ml-auto text-white"
                  >
                    {isSubmitting ? (
                      <div className="flex items-center">
                        <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                          <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {t("signUp.buttons.submitting")}
                      </div>
                    ) : (
                      isSubmitted ? t("signUp.buttons.submitted") : t("signUp.buttons.submit")
                    )}
                  </Button>
                )}
              </div>
              {!isSubmitting && (
                <div className="w-full text-center text-sm text-muted-foreground">
                  {t("signUp.alreadyHaveAccount")}{' '}
                  <Link href={`/${currentLocale}/sign-in`} className="text-primary hover:underline font-medium">
                    {t("signUp.signInLink")}
                  </Link>
                </div>
              )}
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
