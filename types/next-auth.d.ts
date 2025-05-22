import { DefaultSession, DefaultUser } from "next-auth";
import { JWT as DefaultJWT } from "next-auth/jwt";

// Define the user status type
export type UserStatus = 'PENDING' | 'APPROVED' | 'REJECTED';

declare module "next-auth" {
  interface Session {
    user: {
      id: string;
      role: string;
      status: UserStatus;
      emailVerified: Date | null;
    } & DefaultSession["user"];
  }

  interface User extends DefaultUser {
    role: string;
    status: UserStatus;
    emailVerified: Date | null;
  }
}

declare module "next-auth/jwt" {
  interface JWT extends Omit<DefaultJWT, 'sub'> {
    sub: string;
    role: string;
    status: UserStatus;
    emailVerified: Date | null;
  }
}

export interface AuthUser {
  id: string;
  email: string;
  name?: string | null;
  role: string;
  status: UserStatus;
  emailVerified: Date | null;
}
