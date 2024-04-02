<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\EntitiesRelated;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMain;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic2Test extends TestCaseEntitiesRelated
{
    public function testSomething(): void
    {
        $doctrineUpdatingMap = [
            Setting::class => [
                User::class => [
                    'relations' => 'user',
                    'changed_fields' => [
                        'testBoolean',
                    ],
                ],
                SettingMain::class => [
                    'relations' => 'settingMain',
                    'changed_fields' => [
                        'testString',
                    ],
                ],
            ],
        ];

        $setting = new Setting();
        $this->save([$setting]);

        $settingMain = new SettingMain();
        $settingMain->setSetting($setting);
        $this->save([$settingMain]);

        $user = new User();
        $user->setSetting($setting);
        $this->save([$user]);

        $this->assertEquals([], $this->entitiesRelatedBuilder->build($setting, $doctrineUpdatingMap, ChangedEntity::TYPE_UPDATE, ['testFloat']));
        $this->assertEquals([
            'user' => [
                1 => $user,
            ],
        ], $this->entitiesRelatedBuilder->build($setting, $doctrineUpdatingMap, ChangedEntity::TYPE_UPDATE, ['testBoolean']));

        $this->assertEquals([
            'user' => [
                1 => $user,
            ],
            'settingMain' => [
                1 => $settingMain,
            ],
        ], $this->entitiesRelatedBuilder->build($setting, $doctrineUpdatingMap, ChangedEntity::TYPE_UPDATE, ['testBoolean', 'testString']));
    }
}
