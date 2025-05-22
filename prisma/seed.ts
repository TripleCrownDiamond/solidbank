import { PrismaClient, UserStatus } from '@prisma/client'
import bcrypt from 'bcrypt'

const prisma = new PrismaClient()

async function main() {
  const hashedPassword = await bcrypt.hash('Admin@SolidBank2025!', 10)

  const admin = await prisma.user.upsert({
    where: { email: 'admin@solidbank.com' },
    update: {
      // Explicitly update these fields to ensure they're always set correctly
      password: hashedPassword,
      role: 'admin',
      status: UserStatus.APPROVED,
      currency: 'EUR',
      emailVerified: new Date(),
    },
    create: {
      firstName: 'Admin',
      lastName: 'User',
      email: 'admin@solidbank.com',
      password: hashedPassword,
      role: 'admin',
      status: UserStatus.APPROVED,
      currency: 'EUR',
      emailVerified: new Date(),
    },
  })

  console.log('✅ Admin created:', admin)
}

main()
  .catch((e) => {
    console.error(e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
