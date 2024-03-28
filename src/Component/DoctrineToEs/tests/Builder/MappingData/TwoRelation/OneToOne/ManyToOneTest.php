<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TwoRelation\OneToOne;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingGroup;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, true, '', '', []);

        $settingGroup = $this->populateEntity(new SettingGroup());
        $setting = $this->populateEntity(new Setting());
        $setting->setSettingGroup($settingGroup);
        $this->save([$setting]);
        $user = $this->populateEntity(new User());
        $user->setSetting($setting);
        $this->save([$user]);

        $config = [
            'setting' => [
                'settingGroup' => [],
            ],
        ];

        $mappingTestSetting = $this->mappingTest;
        $mappingTestSetting['settingGroup'] = [
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
        $dataTestSetting['settingGroup'] = $this->dataTest;

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $dataTestSetting,
        ]), $data);
    }
}
