<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\EntitiesRelated;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\LocationItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseEntitiesRelated
{
    public function testSomething(): void
    {
        $data = [
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
            ],
        ];

        $user = new User();
        $setting = new Setting();
        $user->setSetting($setting);

        $this->save([$setting, $user]);
        $this->assertEquals([], $this->entitiesRelatedBuilder->build($setting, $data, ['testString']));
        $this->assertEquals([
            'user' => [
                1 => $user,
            ]
        ], $this->entitiesRelatedBuilder->build($setting, $data, ['testBoolean']));

        $this->assertEquals(1, 1);
    }
}
