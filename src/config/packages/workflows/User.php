<?php
declare(strict_types=1);

use App\Entity\User;

return [
    'type' => 'state_machine',
    'marking_store' => [
        'type' => 'method',
        'property' => 'place',
    ],
    'initial_marking' => [User::PLACE_IS_CREATED],
    'supports' => [User::class],
    'places' => User::getPlacesIndexes(),
    'transitions' => [
        User::TRANS_VERIFICATION => [
            'from' => User::PLACE_IS_CREATED,
            'to' => User::PLACE_IS_VERIFIED
        ],
        User::TRANS_HAS_PUBLICATION => [
            'from' => User::PLACE_IS_VERIFIED,
            'to' => User::PLACE_IS_ACTIVE,
        ],
        User::TRANS_DISABLE => [
            'from' => [User::PLACE_IS_ACTIVE,User::PLACE_IS_CREATED,User::PLACE_IS_VERIFIED],
            'to' => User::PLACE_IS_DISABLED,
        ]
    ]
];