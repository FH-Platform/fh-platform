<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic3Test extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $classNames = [
            User::class => [
                'roles' => [
                    'testBigint',
                ],
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
        ];

        $this->assertEquals([
            Role::class => [
                User::class => [
                    'relations' => 'users',
                    'changed_fields' => [
                        'testBigint',
                    ],
                ],
            ],
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
        ], $this->updatingMapBuilder->build($classNames));
    }
}
