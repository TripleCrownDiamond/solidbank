import { getServerSession } from "next-auth/next";
import { authOptions } from "../[...nextauth]/route";
import { NextResponse } from "next/server";
import { Session } from "next-auth";

interface SessionUser {
  id: string;
  name?: string | null;
  email?: string | null;
  role: string;
  status: 'PENDING' | 'APPROVED' | 'REJECTED';
  emailVerified: Date | null;
}

export async function GET() {
  try {
    const session = await getServerSession(authOptions);
    
    if (!session?.user) {
      return NextResponse.json({ user: null }, { status: 200 });
    }
    
    // Type assertion to our custom session user type
    const sessionUser = session.user as SessionUser;
    
    // Return only the necessary user data
    return NextResponse.json({
      user: {
        id: sessionUser.id,
        name: sessionUser.name,
        email: sessionUser.email,
        role: sessionUser.role,
        status: sessionUser.status,
        emailVerified: sessionUser.emailVerified,
      },
    }, { status: 200 });
  } catch (error) {
    console.error('Session error:', error);
    return NextResponse.json(
      { error: 'Internal Server Error' },
      { status: 500 }
    );
  }
}
