<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the translations routes.
    |
    */
    'route' => [
        'prefix' => 'dashboard/config',
        'middleware' => [
            'web',
            'auth',
            'role:admin',
        ],
    ],
];
