<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TwoRelation\OneToOne;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMain;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneTest extends TestCaseMappingData
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

        $config = [
            'setting' => [
                'settingMain' => [],
            ],
        ];

        $mappingTestSetting = $this->mappingTest;
        $mappingTestSetting['settingMain'] = [
            'type' => 'object',
            'properties' => $this->mappingTest,
        ];

        $mapping = $this->mappingProvider->build($index, $config);
        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $mappingTestSetting,
            ],
        ]), $mapping);

        $data = $this->dataProvider->build($index, $user, $config);

        $dataTestSetting = $this->dataTest;
        $dataTestSetting['settingMain'] = $this->dataTest;

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $dataTestSetting,
        ]), $data);
    }
}
