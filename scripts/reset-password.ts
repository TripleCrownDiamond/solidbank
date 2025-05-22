import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function resetPassword(email: string, newPassword: string) {
  try {
    console.log(`Resetting password for: ${email}`);
    
    // Hash the new password
    const salt = await bcrypt.genSalt(10);
    const hashedPassword = await bcrypt.hash(newPassword, salt);
    
    // Update the user's password
    const user = await prisma.user.update({
      where: { email },
      data: {
        password: hashedPassword,
      },
      select: {
        id: true,
        email: true,
        status: true,
        emailVerified: true,
      },
    });

    console.log('✅ Password updated successfully');
    console.log('User:', user);
    return user;
  } catch (error) {
    console.error('Error resetting password:', error);
    throw error;
  } finally {
    await prisma.$disconnect();
  }
}

// Get email and new password from command line arguments
const [email, newPassword] = process.argv.slice(2);

if (!email || !newPassword) {
  console.error('Usage: npx ts-node scripts/reset-password.ts <email> <new-password>');
  process.exit(1);
}

// Run the password reset
resetPassword(email, newPassword)
  .then(() => {
    console.log('🎉 Password reset successful!');
    process.exit(0);
  })
  .catch(error => {
    console.error('❌ Error:', error.message);
    process.exit(1);
  });
