<?php
return [
    'uri' => '/admin',
    /**
     * project specific settings (white label)
     */
    'project' => [
        'author' => 'kiwi suite GmbH',
        'copyright' => '2018',
        'description' => 'Kiwi Admin',
        'name' => 'Kiwi',
        'poweredBy' => true,
        'mediaUrl' => '',
        'logo' => '',
        'icon' => '',
        'background' => '',
    ],
    /**
     *
     */
    'navigation' => [
        [
            'name' => 'Media',
            'url' => '/media',
            'icon' => 'fa fa-image',
            'permissions' => [
                'admin.api.media.index'
            ],
            'roles' => []
        ],
        [
            'name' => 'Users',
            'url' => '/user',
            'icon' => 'fa fa-users',
            'permissions' => [],
            'roles' => [
                'admin'
            ]
        ],
    ]
];
