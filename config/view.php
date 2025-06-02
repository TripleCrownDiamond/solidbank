<?php

return [
    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the paths where your components are located.
    |
    */

    'components' => [
        'mail' => [
            'paths' => [
                resource_path('views/vendor/mail'),
            ],
        ],
    ],
];
