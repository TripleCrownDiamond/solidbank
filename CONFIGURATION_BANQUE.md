# Configuration Rapide de la Banque

Ce système permet de configurer rapidement toutes les informations de la banque dans le projet.

## Fichiers créés

-   `configure-bank.php` : Script principal de configuration
-   `configure-bank.bat` : Script batch pour Windows
-   `bank-config.json` : Fichier de configuration des paramètres
-   `CONFIGURATION_BANQUE.md` : Ce fichier d'aide

## Utilisation

### Méthode 1 : Via le fichier batch (Windows)

1. **Modifiez la configuration** :

    - Ouvrez le fichier `bank-config.json`
    - Modifiez les valeurs selon vos besoins :
        ```json
        {
            "bank_name": "Ma Nouvelle Banque",
            "bank_name_hyphen": "ma-nouvelle-banque",
            "bank_email": "contact@ma-nouvelle-banque.com",
            "bank_phone": "+33987654321",
            "bank_address": "456 Avenue des Finances, Lyon, France",
            "bank_swift": "MANVFRPP",
            "account_prefix": "MANV",
            "logo_url": "img/ma-nouvelle-banque.svg"
        }
        ```

2. **Exécutez la configuration** :
    - Double-cliquez sur `configure-bank.bat`
    - Suivez les instructions à l'écran
    - Confirmez avec 'y' quand demandé

### Méthode 2 : Via la ligne de commande

1. Modifiez `bank-config.json` comme ci-dessus
2. Exécutez :
    ```bash
    php configure-bank.php
    ```

## Ce qui est modifié

Le script modifie automatiquement :

### Fichiers de configuration

-   `database/seeders/ConfigSeeder.php` : Toutes les informations de la banque
-   `.env` : Nom de l'application et emails

### Remplacement global dans le projet

-   `Wolf Developpe` → Votre nom de banque
-   `Wolf-Developpe` → Votre nom avec tirets
-   `privedyme-bank` → Votre nom en minuscules
-   `contact@trade-europe.online` → Votre email
-   `contact@trade-europe.online` → Votre email

### Actions automatiques

-   Migration fresh de la base de données
-   Exécution des seeders
-   Mise à jour de la configuration

## Paramètres de configuration

| Paramètre          | Description                       | Exemple                  |
| ------------------ | --------------------------------- | ------------------------ |
| `bank_name`        | Nom complet de la banque          | "Ma Banque"              |
| `bank_name_hyphen` | Nom avec tirets (pour URLs, etc.) | "ma-banque"              |
| `bank_email`       | Email de contact principal        | "contact@ma-banque.com"  |
| `bank_phone`       | Numéro de téléphone               | "+33123456789"           |
| `bank_address`     | Adresse complète                  | "123 Rue Example, Paris" |
| `bank_swift`       | Code SWIFT/BIC                    | "MBANFRPP"               |
| `account_prefix`   | Préfixe des comptes               | "MBAN"                   |
| `logo_url`         | Chemin vers le logo               | "img/ma-banque.svg"      |

## Notes importantes

-   ⚠️ **Sauvegardez votre base de données** avant d'exécuter le script (il fait un `migrate:fresh`)
-   Le script recherche et remplace dans tous les fichiers `.php`, `.blade.php`, `.js`, `.vue`
-   Assurez-vous que votre fichier logo existe dans le dossier `public/img/`
-   Le script demande confirmation avant d'appliquer les changements

## Dépannage

### Erreur "Le fichier bank-config.json n'existe pas"

-   Vérifiez que le fichier `bank-config.json` est présent dans le dossier racine
-   Créez-le s'il n'existe pas en copiant l'exemple ci-dessus

### Erreur "JSON invalide"

-   Vérifiez la syntaxe de votre fichier JSON
-   Utilisez un validateur JSON en ligne si nécessaire
-   Assurez-vous que toutes les chaînes sont entre guillemets

### Erreur de migration

-   Vérifiez que votre base de données est accessible
-   Assurez-vous que PHP et Artisan fonctionnent correctement
-   Vérifiez les permissions d'écriture sur les fichiers

## Exemple complet

Pour configurer une banque "EcoBank" :

```json
{
    "bank_name": "EcoBank",
    "bank_name_hyphen": "ecobank",
    "bank_email": "contact@ecobank.fr",
    "bank_phone": "+33145678901",
    "bank_address": "789 Boulevard Écologique, Marseille, France",
    "bank_swift": "ECOFRPP",
    "account_prefix": "ECO",
    "logo_url": "img/ecobank.svg"
}
```

Puis exécutez `configure-bank.bat` ou `php configure-bank.php`.
