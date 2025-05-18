// middleware.ts
import { createI18nMiddleware } from "next-international/middleware";
import { NextRequest, NextResponse } from "next/server";
import { clerkMiddleware, createRouteMatcher } from "@clerk/nextjs/server";

// Définissez d'abord les routes publiques et protégées pour Clerk
const isProtectedRoute = createRouteMatcher([
  // Ajoutez ici les chemins que vous voulez protéger avec Clerk, par exemple :
  // '/dashboard(.*)',
  // '/settings(.*)',
]);

// Configurez le middleware d'internationalisation
const I18nMiddleware = createI18nMiddleware({
  locales: ["en", "fr"],
  defaultLocale: "fr",
});

export default clerkMiddleware(async (auth, req) => {
  // Si la route est protégée, Clerk s'en occupe et redirige si non authentifié.
  // auth().protect() gère la redirection.
  if (isProtectedRoute(req)) await auth.protect();

  // Appliquez ensuite le middleware d'internationalisation.
  // Clerk recommande de retourner directement le résultat du middleware suivant.
  return I18nMiddleware(req);
});

export const config = {
  matcher: [
    /*
     * Correspond à tous les chemins de requête sauf ceux qui commencent par :
     * - _next (internes Next.js, y compris /static et /image)
     * - api (routes API)
     * - favicon.ico (fichier favicon)
     * - Autres fichiers statiques communs (par exemple, .svg, .png)
     *
     * Cette expression régulière est plus proche de celle recommandée par Clerk.
     * Elle exclut plus largement les fichiers statiques et les internes de Next.js.
     */
    "/((?!api|_next/static|_next/image|.*\\.(?:svg|png|jpg|jpeg|gif|webp|ico|css|js)$).*)",
    // La documentation de Clerk suggère aussi de toujours exécuter le middleware pour les routes API si elles doivent être protégées.
    // Si vos routes API utilisent Clerk pour l'authentification, vous pourriez avoir besoin de les inclure explicitement
    // ou d'ajuster le regex ci-dessus pour ne pas les exclure si elles ne sont pas sous /api/.
    // Cependant, si vos routes /api sont gérées séparément ou n'ont pas besoin d'i18n, l'exclusion est correcte.
    // Si Clerk doit aussi matcher les API pour protection :
    // '/(api|trpc)(.*)', // Décommentez et ajustez si nécessaire.
  ],
};
