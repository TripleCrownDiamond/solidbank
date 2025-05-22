/*
  Warnings:

  - You are about to drop the column `approved` on the `User` table. All the data in the column will be lost.

*/
-- RedefineTables
PRAGMA defer_foreign_keys=ON;
PRAGMA foreign_keys=OFF;
CREATE TABLE "new_User" (
    "id" TEXT NOT NULL PRIMARY KEY,
    "firstName" TEXT NOT NULL,
    "lastName" TEXT NOT NULL,
    "email" TEXT NOT NULL,
    "emailVerified" DATETIME,
    "password" TEXT NOT NULL,
    "role" TEXT NOT NULL DEFAULT 'user',
    "status" TEXT NOT NULL DEFAULT 'PENDING',
    "balance" REAL NOT NULL DEFAULT 0.0,
    "currency" TEXT NOT NULL,
    "createdAt" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updatedAt" DATETIME NOT NULL,
    "birthDate" DATETIME,
    "gender" TEXT,
    "maritalStatus" TEXT,
    "profession" TEXT,
    "address" TEXT,
    "city" TEXT,
    "postalCode" TEXT,
    "country" TEXT,
    "region" TEXT,
    "phoneNumber" TEXT,
    "accountType" TEXT,
    "identityDocumentUrl" TEXT
);
INSERT INTO "new_User" ("balance", "createdAt", "currency", "email", "emailVerified", "firstName", "id", "lastName", "password", "role", "updatedAt") SELECT "balance", "createdAt", "currency", "email", "emailVerified", "firstName", "id", "lastName", "password", "role", "updatedAt" FROM "User";
DROP TABLE "User";
ALTER TABLE "new_User" RENAME TO "User";
CREATE UNIQUE INDEX "User_email_key" ON "User"("email");
PRAGMA foreign_keys=ON;
PRAGMA defer_foreign_keys=OFF;
