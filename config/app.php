<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | Application Name
     * |--------------------------------------------------------------------------
     * |
     * | This value is the name of your application, which will be used when the
     * | framework needs to place the application's name in a notification or
     * | other UI elements where an application name needs to be displayed.
     * |
     */
    'name' => env('APP_NAME', 'Privedyme Bank'),

    /*
     * |--------------------------------------------------------------------------
     * | Application Environment
     * |--------------------------------------------------------------------------
     * |
     * | This value determines the "environment" your application is currently
     * | running in. This may determine how you prefer to configure various
     * | services the application utilizes. Set this in your ".env" file.
     * |
     */
    'env' => env('APP_ENV', 'production'),

    /*
     * |--------------------------------------------------------------------------
     * | Application Debug Mode
     * |--------------------------------------------------------------------------
     * |
     * | When your application is in debug mode, detailed error messages with
     * | stack traces will be shown on every error that occurs within your
     * | application. If disabled, a simple generic error page is shown.
     * |
     */
    'debug' => (bool) env('APP_DEBUG', false),

    /*
     * |--------------------------------------------------------------------------
     * | Application URL
     * |--------------------------------------------------------------------------
     * |
     * | This URL is used by the console to properly generate URLs when using
     * | the Artisan command line tool. You should set this to the root of
     * | the application so that it's available within Artisan commands.
     * |
     */
    'url' => env('APP_URL', 'http://localhost'),

    /*
     * |--------------------------------------------------------------------------
     * | Application Timezone
     * |--------------------------------------------------------------------------
     * |
     * | Here you may specify the default timezone for your application, which
     * | will be used by the PHP date and date-time functions. The timezone
     * | is set to "UTC" by default as it is suitable for most use cases.
     * |
     */
    'timezone' => 'UTC',

    /*
     * |--------------------------------------------------------------------------
     * | Application Locale Configuration
     * |--------------------------------------------------------------------------
     * |
     * | The application locale determines the default locale that will be used
     * | by Laravel's translation / localization methods. This option can be
     * | set to any locale for which you plan to have translation strings.
     * |
     */
    'locale' => env('APP_LOCALE', 'fr'),  // Langue par défaut : français
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),  // Langue de secours : anglais
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'available_locales' => ['fr', 'en', 'es', 'de', 'pt'],

    /*
     * |--------------------------------------------------------------------------
     * | Company Information
     * |--------------------------------------------------------------------------
     * |
     * | These values define the company contact information that will be used
     * | throughout the application, particularly in the footer and contact pages.
     * |
     */
    'company_address_line1' => env('COMPANY_ADDRESS_LINE1', '123 Rue de la Banque'),
    'company_address_line2' => env('COMPANY_ADDRESS_LINE2', 'Quartier Financier'),
    'company_address_line3' => env('COMPANY_ADDRESS_LINE3', '75001 Paris, France'),
    'company_phone' => env('COMPANY_PHONE', '+33 1 23 45 67 89'),
    'company_email' => env('COMPANY_EMAIL', 'contact@privedymeeu.com'),

    /*
     * |--------------------------------------------------------------------------
     * | Encryption Key
     * |--------------------------------------------------------------------------
     * |
     * | This key is utilized by Laravel's encryption services and should be set
     * | to a random, 32 character string to ensure that all encrypted values
     * | are secure. You should do this prior to deploying the application.
     * |
     */
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Autoloaded Service Providers
     * |--------------------------------------------------------------------------
     *
     * | The service providers listed here will be automatically loaded on the
     * | request to your application. Feel free to add your own services to
     * | this array to grant expanded functionality to your applications.
     */
    'providers' => [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        App\Providers\LocaleServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\BankConfigServiceProvider::class,
        App\Providers\MailServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Maintenance Mode Driver
     * |--------------------------------------------------------------------------
     * |
     * | These configuration options determine the driver used to determine and
     * | manage Laravel's "maintenance mode" status. The "cache" driver will
     * | allow maintenance mode to be controlled across multiple machines.
     * |
     * | Supported drivers: "file", "cache"
     * |
     */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Default Currency
     * |--------------------------------------------------------------------------
     * |
     * | This value determines the default currency used throughout the application
     * | when no specific currency is defined. This can be overridden by setting
     * | the APP_DEFAULT_CURRENCY environment variable.
     * |
     */
    'default_currency' => env('APP_DEFAULT_CURRENCY', 'EUR'),
];
