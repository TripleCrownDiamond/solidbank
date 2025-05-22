import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function checkCredentials(email: string, password: string) {
  try {
    console.log(`Checking credentials for: ${email}`);
    
    // Find the user
    const user = await prisma.user.findUnique({
      where: { email },
      select: {
        id: true,
        email: true,
        password: true,
        status: true,
        emailVerified: true,
        role: true,
        firstName: true,
        lastName: true
      },
    });

    if (!user) {
      console.log('❌ User not found');
      return null;
    }

    console.log('✅ User found:', {
      id: user.id,
      email: user.email,
      status: user.status,
      emailVerified: user.emailVerified,
      role: user.role,
      hasPassword: !!user.password
    });

    // Check if password exists
    if (!user.password) {
      console.log('❌ No password set for user');
      return null;
    }

    // Compare passwords
    const isValid = await bcrypt.compare(password, user.password);
    console.log(`🔑 Password ${isValid ? '✅ valid' : '❌ invalid'}`);

    if (isValid) {
      console.log('✅ Credentials are valid!');
      return {
        id: user.id,
        email: user.email,
        name: `${user.firstName} ${user.lastName}`,
        role: user.role,
        status: user.status,
        emailVerified: user.emailVerified
      };
    }

    return null;
  } catch (error) {
    console.error('Error checking credentials:', error);
    return null;
  } finally {
    await prisma.$disconnect();
  }
}

// Get email and password from command line arguments
const [email, password] = process.argv.slice(2);

if (!email || !password) {
  console.error('Usage: npx ts-node scripts/check-credentials.ts <email> <password>');
  process.exit(1);
}

// Run the check
checkCredentials(email, password)
  .then(user => {
    if (user) {
      console.log('🎉 Authentication successful!');
      console.log('User:', user);
    } else {
      console.log('❌ Authentication failed');
    }
    process.exit(0);
  })
  .catch(error => {
    console.error('Error:', error);
    process.exit(1);
  });
