<?php
return [
    /**
     * where the admin resides at
     * default: admin
     *
     * TODO: transform this into an absolute url by default if it is not already in the project's admin config
     */
    'uri' => 'admin',

    'security' => [
        /**
         * specify hosts that are allowed to access the api e.g. the local admin development host
         */
        'allow' => [
            // localhost:4200,
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
