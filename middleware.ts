// middleware.ts
import { createI18nMiddleware } from "next-international/middleware";
import { NextRequest, NextResponse } from "next/server";

const locales = ["en", "fr"];
const defaultLocale = "fr";

const publicFileRegex = /\.(.*)$/;
const excludePaths = [
  '/api',
  '/_next',
  '/favicon.ico',
  '/healthz',
  '/_vercel',
  '/sitemap',
  '/robots.txt'
];

const I18nMiddleware = createI18nMiddleware({
  locales,
  defaultLocale,
  urlMappingStrategy: 'rewriteDefault'
});

export function middleware(req: NextRequest) {
  const { pathname } = req.nextUrl;
  
  // Skip middleware for static files and excluded paths
  if (
    excludePaths.some(path => pathname.startsWith(path)) ||
    publicFileRegex.test(pathname)
  ) {
    return NextResponse.next();
  }

  // Check if the path is missing a locale
  const pathnameHasLocale = locales.some(
    (locale) => pathname.startsWith(`/${locale}/`) || pathname === `/${locale}`
  );

  // Redirect to default locale if no locale is present
  if (!pathnameHasLocale) {
    // For the root path, redirect to default locale
    if (pathname === '/') {
      return NextResponse.redirect(new URL(`/${defaultLocale}`, req.url));
    }
    
    // For other paths, prepend the default locale
    return NextResponse.redirect(
      new URL(`/${defaultLocale}${pathname.startsWith('/') ? '' : '/'}${pathname}`, req.url)
    );
  }

  // Let the i18n middleware handle the rest
  return I18nMiddleware(req);
}

export const config = {
  matcher: [
    "/((?!api|_next/static|_next/image|_next/data|healthz|.*\..*).*)",
    // "/(api|trpc)(.*)" // Uncomment if you have API routes that need matching
  ],
};
