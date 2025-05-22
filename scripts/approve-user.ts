import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function approveUser(email: string) {
  try {
    console.log(`Approving user: ${email}`);
    
    // Update the user's status and verify their email
    const user = await prisma.user.update({
      where: { email },
      data: {
        status: 'APPROVED',
        emailVerified: new Date(),
      },
      select: {
        id: true,
        email: true,
        status: true,
        emailVerified: true,
      },
    });

    console.log('✅ User approved and email verified');
    console.log('User:', user);
    return user;
  } catch (error) {
    console.error('Error approving user:', error);
    throw error;
  } finally {
    await prisma.$disconnect();
  }
}

// Get email from command line arguments
const [email] = process.argv.slice(2);

if (!email) {
  console.error('Usage: npx ts-node scripts/approve-user.ts <email>');
  process.exit(1);
}

// Run the approval
approveUser(email)
  .then(() => {
    console.log('🎉 User approved successfully!');
    process.exit(0);
  })
  .catch(error => {
    console.error('❌ Error:', error.message);
    process.exit(1);
  });
