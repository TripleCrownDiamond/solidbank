# Custom Artisan Commands

This file documents all custom Artisan commands created for the SolidBank project.

## Available Commands

### `users:export-and-refresh`

**Description:** Exports existing users to multiple file formats, runs fresh migration and seeding, then generates new users.

**Usage:**

```bash
php artisan users:export-and-refresh {count=10}
```

**Parameters:**

-   `count` (optional): Number of new users to generate after refresh. Default: 10

**What it does:**

1. **Step 0:** Cleans old export files from `storage/app/exports/`
2. **Step 1:** Exports existing users to:
    - JSON format (`users_export_TIMESTAMP.json`)
    - CSV format (`users_export_TIMESTAMP.csv`)
    - Excel CSV format (`users_export_excel_TIMESTAMP.csv`)
3. **Step 2:** Runs `php artisan migrate:fresh`
4. **Step 3:** Runs `php artisan db:seed`
5. **Step 4:** Creates specified number of new users with accounts using UserFactory

**Export Location:** `storage/app/exports/`

**Examples:**

```bash
# Generate 100 new users after export and refresh
php artisan users:export-and-refresh 100

# Generate 5 new users after export and refresh
php artisan users:export-and-refresh 5

# Use default count (10 users)
php artisan users:export-and-refresh
```

**Features:**

-   Automatic cleanup of old export files
-   Progress bar for user generation
-   Detailed statistics and feedback
-   Multiple export formats for compatibility
-   Complete database refresh workflow

**Troubleshooting:**

-   If you get a "-e option does not exist" error, ensure you're using the correct syntax without curly braces
-   Correct: `php artisan users:export-and-refresh 100`
-   Incorrect: `php artisan users:export-and-refresh {count=100}`

---

## Adding New Commands

To add a new custom command to this documentation:

1. Create the command using `php artisan make:command CommandName`
2. Implement the command logic
3. Document it in this file following the format above
4. Include usage examples and parameter descriptions

## Command Location

All custom commands are stored in: `app/Console/Commands/`
