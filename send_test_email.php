<?php

// Script simple pour envoyer un email de test
echo "=== Envoi d'email de test ===\n";
echo "Email: gagbahungba2010@gmail.com\n\n";

// Commandes à exécuter dans tinker
$commands = [
    "use App\\Mail\\AccountActivationMail;",
    "use App\\Models\\User;",
    "use Illuminate\\Support\\Facades\\Mail;",
    "use Illuminate\\Support\\Facades\\URL;",
    "\$testUser = new User(['name' => 'Test User', 'email' => 'gagbahungba2010@gmail.com']);",
    "\$url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['id' => 999, 'hash' => sha1('gagbahungba2010@gmail.com')]);",
    "\$mail = new AccountActivationMail(\$testUser, \$url);",
    "Mail::to('gagbahungba2010@gmail.com')->send(\$mail);",
    "echo 'Email envoyé avec succès!';"
];

foreach ($commands as $i => $command) {
    echo ($i + 1) . ". $command\n";
}

echo "\n=== Instructions ===\n";
echo "Exécutez: php artisan tinker\n";
echo "Puis copiez-collez les commandes ci-dessus une par une.\n";