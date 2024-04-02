<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\EntitiesRelated;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntity;

class BasicTest extends TestCaseEntitiesRelated
{
    public function testSomething(): void
    {
        $doctrineUpdatingMap = [
            Setting::class => [
                User::class => [
                    'relations' => 'user',
                    'changed_fields' => [
                        'id',
                        'testBoolean',
                    ],
                ],
            ],
        ];

        $user = new User();
        $setting = new Setting();
        $user->setSetting($setting);

        $this->save([$setting, $user]);
        $this->assertEquals([], $this->entitiesRelatedBuilder->build($setting, $doctrineUpdatingMap, ChangedEntity::TYPE_UPDATE, ['testString']));
        $this->assertEquals([
            'user' => [
                1 => $user,
            ],
        ], $this->entitiesRelatedBuilder->build($setting, $doctrineUpdatingMap, ChangedEntity::TYPE_UPDATE, ['testBoolean']));
    }
}
