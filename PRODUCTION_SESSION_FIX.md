# Guide de résolution des problèmes de session en production

## Problèmes identifiés

1. **Configuration HTTPS manquante** : Les cookies de session ne sont pas configurés pour HTTPS
2. **Domaine de session non défini** : Peut causer des problèmes de persistance
3. **Durée de session trop longue** : 4320 minutes (3 jours) peut causer des problèmes
4. **Configuration CSRF insuffisante** pour la production

## Solutions à appliquer

### 1. Mise à jour du fichier .env en production

```env
# Configuration de session sécurisée pour HTTPS
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=.wolf-developpe.com
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Configuration CSRF
CSRF_COOKIE_SECURE=true
CSRF_COOKIE_HTTP_ONLY=false
CSRF_COOKIE_SAME_SITE=lax
```

### 2. Configuration Apache/Nginx

Assurez-vous que votre serveur web est configuré pour HTTPS et que les en-têtes de sécurité sont correctement définis.

### 3. Vérifications à effectuer

1. **Table sessions** : Vérifiez que la table existe et est accessible
2. **Permissions** : Vérifiez les permissions sur le dossier storage
3. **HTTPS** : Assurez-vous que le site fonctionne en HTTPS
4. **Domaine** : Vérifiez que le domaine est correctement configuré

### 4. Commandes à exécuter après mise à jour

```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table
php artisan migrate
php artisan optimize
```

### 5. Test de la configuration

Après application des modifications :

1. Testez la connexion
2. Vérifiez que les sessions persistent
3. Testez l'accès aux documents
4. Vérifiez les logs d'erreur

## Notes importantes

-   La durée de session a été réduite à 120 minutes (2 heures)
-   Le chiffrement des sessions est activé pour plus de sécurité
-   Les cookies sont configurés pour HTTPS uniquement
-   Le domaine inclut les sous-domaines avec le préfixe point
