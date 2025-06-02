<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail Component Paths
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the paths where your mail components
    | are located. These paths will be used by Laravel when resolving mail
    | component names from your mail views.
    |
    */

    'paths' => [
        resource_path('views/vendor/mail'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option specifies the path where compiled mail view files will be
    | stored. This should be a path that your web server can write to.
    |
    */
    'compiled' => env(
        'MAIL_VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
