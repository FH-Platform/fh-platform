<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\TwoRelation\OneToOne;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\OneRelation\TestCaseOneRelation;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMain;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $settingMain = $this->populateEntity(new SettingMain());
        $setting = $this->populateEntity(new Setting());
        $settingMain->setSetting($setting);
        $this->save([$settingMain]);
        $user = $this->populateEntity(new User());
        $user->setSetting($setting);
        $this->save([$user]);

        $mapping = $this->mappingProvider->provide($index, ['setting' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $this->dataProvider->provide($index, $user, [
            'setting' => [
                'settingMain' => []
            ]
        ]);

        $dataTestSetting = $this->dataTest;
        $dataTestSetting['settingMain'] = $this->dataTest;

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $dataTestSetting,
        ]), $data);
    }
}
