<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\UpdatingMap;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMain;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic3Test extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $connections = [
            'default' => [
                User::class => [
                    'roles' => [
                        'testBigint',
                    ],
                    'setting' => [
                        'id',
                        'testBoolean'
                    ],
                    'location' => [
                        'testString',
                        'locationItems' => [
                            'testFloat',
                        ]
                    ],
                ],
                Location::class => [
                    'locationItems' => [
                        'testString',
                    ]
                ]
            ]
        ];

        $this->assertEquals([
            'default' => [
                Role::class => [
                    User::class => [
                        "relations" => "users",
                        "changed_fields" => [
                            'testBigint',
                        ],
                    ],
                ],
                Setting::class => [
                    User::class => [
                        "relations" => "user",
                        "changed_fields" => [
                            "id",
                            "testBoolean",
                        ],
                    ],
                ],
                Location::class => [
                    User::class => [
                        "relations" => "users",
                        "changed_fields" =>
                            [
                                "testString"
                            ],
                    ]
                ],
                LocationItem::class => [
                    User::class => [
                        "relations" => "location.users",
                        "changed_fields" => [
                            "testFloat"
                        ],
                    ],
                    Location::class => [
                        "relations" => "location",
                        "changed_fields" => [
                            "testString"
                        ],
                    ],
                ],
            ]
        ], $this->updatingMapBuilder->build($connections));
    }
}
