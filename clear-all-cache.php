<?php

// Script pour vider tous les caches sur Hostinger

echo "Début du vidage des caches...\n";

// Vider le cache Laravel
echo "1. Vidage du cache Laravel...\n";
exec('php artisan cache:clear', $output1, $return1);
if ($return1 === 0) {
    echo "   ✓ Cache Laravel vidé\n";
} else {
    echo "   ✗ Erreur lors du vidage du cache Laravel\n";
}

// Vider le cache des vues
echo "2. Vidage du cache des vues...\n";
exec('php artisan view:clear', $output2, $return2);
if ($return2 === 0) {
    echo "   ✓ Cache des vues vidé\n";
} else {
    echo "   ✗ Erreur lors du vidage du cache des vues\n";
}

// Vider le cache de configuration
echo "3. Vidage du cache de configuration...\n";
exec('php artisan config:clear', $output3, $return3);
if ($return3 === 0) {
    echo "   ✓ Cache de configuration vidé\n";
} else {
    echo "   ✗ Erreur lors du vidage du cache de configuration\n";
}

// Vider le cache des routes
echo "4. Vidage du cache des routes...\n";
exec('php artisan route:clear', $output4, $return4);
if ($return4 === 0) {
    echo "   ✓ Cache des routes vidé\n";
} else {
    echo "   ✗ Erreur lors du vidage du cache des routes\n";
}

// Vider le cache des événements
echo "5. Vidage du cache des événements...\n";
exec('php artisan event:clear', $output5, $return5);
if ($return5 === 0) {
    echo "   ✓ Cache des événements vidé\n";
} else {
    echo "   ✗ Erreur lors du vidage du cache des événements\n";
}

// Vider l'opcache PHP si disponible
echo "6. Vidage de l'opcache PHP...\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "   ✓ Opcache PHP vidé\n";
    } else {
        echo "   ✗ Erreur lors du vidage de l'opcache PHP\n";
    }
} else {
    echo "   - Opcache PHP non disponible\n";
}

// Optimisation complète
echo "7. Optimisation complète...\n";
exec('php artisan optimize:clear', $output7, $return7);
if ($return7 === 0) {
    echo "   ✓ Optimisation complète effectuée\n";
} else {
    echo "   ✗ Erreur lors de l'optimisation\n";
}

echo "\nTous les caches ont été traités !\n";
echo "Veuillez maintenant tester votre application.\n";