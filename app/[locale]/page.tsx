"use client";
import { useI18n } from "../../locales/client";
import { ThemeToggle } from "@/components/theme-toggle";

const Home = () => {
  const t = useI18n();

  return (
    <div className="flex min-h-screen flex-col items-center justify-center">
      <div className="absolute right-4 top-4">
        <ThemeToggle />
      </div>
      <h1 className="text-4xl font-bold">{t("hello")}</h1>
    </div>
  );
};

export default Home;
