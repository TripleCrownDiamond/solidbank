import NextAuth, { type DefaultSession, type NextAuthOptions } from "next-auth";
import CredentialsProvider from "next-auth/providers/credentials";
import { PrismaClient } from "@prisma/client";
import bcrypt from "bcrypt";

const prisma = new PrismaClient();

// Import our custom types
import type { UserStatus } from "@/types/next-auth";

// Extend the built-in types to include our custom fields
declare module "next-auth" {
  interface Session extends DefaultSession {
    user: {
      id: string;
      role: string;
      status: UserStatus;
      emailVerified: Date | null;
    } & DefaultSession["user"];
  }

  interface User {
    id: string;
    role: string;
    status: UserStatus;
    emailVerified: Date | null;
  }
}

declare module "next-auth/jwt" {
  interface JWT {
    role: string;
    status: UserStatus;
    emailVerified: Date | null;
  }
}

export const authOptions: NextAuthOptions = {
  providers: [
    CredentialsProvider({
      name: "Credentials",
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" },
      },
      async authorize(credentials) {
        if (!credentials?.email || !credentials?.password) {
          throw new Error("Email and password are required");
        }

        const user = await prisma.user.findUnique({
          where: { email: credentials.email },
        });

        if (!user) {
          throw new Error("Invalid email or password");
        }

        if (!user.password) {
          throw new Error("Password not set");
        }

        const isValid = await bcrypt.compare(credentials.password, user.password);

        if (!isValid) {
          throw new Error("Invalid email or password");
        }

        if (user.status === 'PENDING') {
          throw new Error("Account pending approval");
        }

        if (user.status === 'REJECTED') {
          throw new Error("Account rejected");
        }

        if (!user.emailVerified) {
          throw new Error("EmailNotVerified");
        }

        // Return user data that will be encoded in the JWT
        return {
          id: user.id,
          email: user.email,
          name: `${user.firstName} ${user.lastName}`,
          role: user.role,
          status: user.status as UserStatus,
          emailVerified: user.emailVerified
        };
      },
    }),
  ],
  callbacks: {
    async jwt({ token, user }) {
      if (user) {
        token.id = user.id;
        token.role = user.role;
        token.status = user.status;
        token.emailVerified = user.emailVerified;
      }
      return token;
    },
    async session({ session, token }) {
      // Add custom fields to the session
      if (session.user) {
        session.user.id = token.sub!;
        session.user.role = token.role;
        session.user.status = token.status as UserStatus;
        session.user.emailVerified = token.emailVerified as Date | null;
      }
      return session;
    },
  },
  session: {
    strategy: "jwt",
  },
  secret: process.env.NEXTAUTH_SECRET,
  pages: {
    signIn: "/sign-in",
    error: "/auth/error",
  },
};

const handler = NextAuth(authOptions);

export { handler as GET, handler as POST };