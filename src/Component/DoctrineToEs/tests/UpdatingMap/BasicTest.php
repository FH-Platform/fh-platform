<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseUpdatingMap
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
                ],
            ],
        ], $this->updatingMapBuilder->build($connections));
    }
}