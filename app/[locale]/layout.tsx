import { Inter, Poppins } from "next/font/google";
import "./globals.css";
import { Providers } from "./providers";
import { ThemeProvider } from "@/components/theme-provider";
import { ClerkClientProvider } from "@/components/clerk-client-provider";

const inter = Inter({
  variable: "--font-sans",
  subsets: ["latin"],
  display: "swap",
});

const poppins = Poppins({
  variable: "--font-heading",
  weight: ["400", "500", "600", "700"],
  subsets: ["latin"],
  display: "swap",
});

export const metadata = {
  title: process.env.APP_NAME || "SolidBank",
  description: "Your app description here",
  icons: {
    icon: [
      { url: "/favicon.ico", sizes: "any" },
      { url: "/icon_blue.svg", type: "image/svg+xml" },
    ],
    apple: [{ url: "/icon_blue.svg" }],
  },
};

export default async function RootLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: { locale: string };
}) {
  const { locale } = await params;

  return (
    <html lang={locale} suppressHydrationWarning>
      <body
        className={`${inter.variable} ${poppins.variable} antialiased`}
        suppressHydrationWarning
      >
        <ThemeProvider>
          <ClerkClientProvider locale={locale}>
            <Providers locale={locale}>{children}</Providers>
          </ClerkClientProvider>
        </ThemeProvider>
      </body>
    </html>
  );
}
