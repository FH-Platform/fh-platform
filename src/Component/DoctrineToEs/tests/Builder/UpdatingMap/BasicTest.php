<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $classNames = [
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
        ];

        $this->assertEquals([
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
        ], $this->updatingMapBuilder->build($classNames));
    }
}
