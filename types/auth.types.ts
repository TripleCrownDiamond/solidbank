import { DefaultSession, DefaultUser } from "next-auth";
import { JWT as DefaultJWT } from "next-auth/jwt";

// Define the user status type
export type UserStatus = 'PENDING' | 'APPROVED' | 'REJECTED';

// Extend the built-in session types
declare module "next-auth" {
  interface Session {
    user: {
      id: string;
      role?: string;
      status?: UserStatus;
      emailVerified?: Date | null;
    } & DefaultSession["user"];
  }

  interface User extends DefaultUser {
    role?: string;
    status?: UserStatus;
    emailVerified?: Date | null;
  }
}

// Extend the JWT token types
declare module "next-auth/jwt" {
  interface JWT extends DefaultJWT {
    role?: string;
    status?: UserStatus;
    emailVerified?: Date | null;
  }
}

// Custom user type for our application
export interface AuthUser {
  id: string;
  email: string;
  name?: string | null;
  role: string;
  status: UserStatus;
  emailVerified: Date | null;
}
