import { NextResponse } from 'next/server';
import { PrismaClient, Gender, AccountType, UserStatus } from '@prisma/client';
import bcrypt from 'bcryptjs';
import * as z from 'zod';

const prisma = new PrismaClient();

// Define Zod schema for input validation (mirrors frontend, but good for backend too)
// This helps ensure data integrity even if frontend validation is bypassed.
const SignUpAPISchema = z.object({
  firstName: z.string().min(1),
  lastName: z.string().min(1),
  birthDate: z.string().refine((date) => !isNaN(Date.parse(date)), { message: "Invalid date format" }), // Expecting ISO string
  gender: z.nativeEnum(Gender),
  maritalStatus: z.string().optional(),
  profession: z.string().optional(),
  address: z.string().min(1),
  country: z.string().min(1),
  city: z.string().min(1),
  postalCode: z.string().min(1),
  region: z.string().optional(),
  phoneNumber: z.string().optional(),
  accountType: z.nativeEnum(AccountType),
  currency: z.string().min(1), // e.g., XOF, EUR, USD
  email: z.string().email(),
  password: z.string().min(6), // Min length can be more strict if needed
  identityDocumentUrl: z.string().url().optional().nullable(),
  addressDocumentUrl: z.string().url().optional().nullable(),
});

export async function POST(request: Request) {
  try {
    const body = await request.json();
    
    // Validate input
    const validationResult = SignUpAPISchema.safeParse(body);
    if (!validationResult.success) {
      return NextResponse.json({ message: 'Invalid input data', errors: validationResult.error.flatten().fieldErrors }, { status: 400 });
    }

    const { 
      email, 
      password, 
      firstName, 
      lastName, 
      birthDate,
      gender,
      maritalStatus,
      profession,
      address,
      country,
      city,
      postalCode,
      region,
      phoneNumber,
      accountType,
      currency,
      identityDocumentUrl,
      addressDocumentUrl 
    } = validationResult.data;

    // Check if user already exists
    const existingUser = await prisma.user.findUnique({
      where: { email },
    });

    if (existingUser) {
      return NextResponse.json({ message: 'User with this email already exists' }, { status: 409 }); // 409 Conflict
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10); // Salt rounds = 10

    // Create user
    const newUser = await prisma.user.create({
      data: {
        firstName,
        lastName,
        email,
        password: hashedPassword,
        birthDate: birthDate ? new Date(birthDate) : undefined,
        gender: gender as Gender | undefined,
        maritalStatus: maritalStatus || undefined,
        profession: profession || undefined,
        address: address || undefined,
        country,
        city,
        postalCode,
        region: region || undefined,
        phoneNumber: phoneNumber || undefined,
        accountType: accountType as AccountType,
        currency,
        identityDocumentUrl: identityDocumentUrl || undefined,
        addressDocumentUrl: addressDocumentUrl || undefined,
        status: UserStatus.PENDING,
        // role is defaulted in schema
        // balance is defaulted in schema
        // emailVerified can be handled later via an email verification flow
      },
    });

    // Don't return password or sensitive details
    const { password: _, ...userWithoutPassword } = newUser;

    return NextResponse.json({ message: 'User created successfully', user: userWithoutPassword }, { status: 201 });

  } catch (error: any) {
    console.error('Sign-up API error:', error);
    // Check for Prisma-specific errors if needed
    // if (error instanceof Prisma.PrismaClientKnownRequestError) { ... }
    return NextResponse.json({ message: 'An unexpected error occurred', error: error.message }, { status: 500 });
  }
}
