<?php
return [
    /**
     * where the admin resides at
     * default: 'admin'
     */
    'uri' => 'admin',

    'security' => [

        /**
         * set session domain to 'localhost' for local admin development
         * default: requested host
         */
        'domain' => 'localhost',
        // 'domain' => null,

        /**
         * specify hosts that are allowed to access the api e.g. the local admin development host
         * add 'http://localhost:port' => true for local admin development
         * these will be added to the CorsMiddleware
         */
        'allow' => [
            'http://localhost:4200' => true,
        ],
    ],

    /**
     * project specific settings for white labeling
     */
    'project' => [
        'author' => 'kiwi suite GmbH',
        'copyright' => '2018 kiwi suite GmbH',
        'description' => 'Kiwi Admin',
        'name' => 'Kiwi',
        'poweredBy' => true,
    ],
];
