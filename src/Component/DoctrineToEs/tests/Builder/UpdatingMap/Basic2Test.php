<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic2Test extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $connections = [
            'default' => [
                User::class => [
                    'setting' => [
                        'id',
                        'testBoolean',
                    ],
                    'location' => [
                        'testString',
                        'locationItems' => [
                            'testFloat',
                        ],
                    ],
                ],
                Location::class => [
                    'locationItems' => [
                        'testString',
                    ],
                ],
            ],
        ];

        $this->assertEquals([
            'default' => [
                Setting::class => [
                    User::class => [
                        'relations' => 'user',
                        'changed_fields' => [
                            'id',
                            'testBoolean',
                        ],
                    ],
                ],
                Location::class => [
                    User::class => [
                        'relations' => 'users',
                        'changed_fields' => [
                            'testString',
                        ],
                    ],
                ],
                LocationItem::class => [
                    User::class => [
                        'relations' => 'location.users',
                        'changed_fields' => [
                            'testFloat',
                        ],
                    ],
                    Location::class => [
                        'relations' => 'location',
                        'changed_fields' => [
                            'testString',
                        ],
                    ],
                ],
            ],
        ], $this->updatingMapBuilder->build($connections));
    }
}
