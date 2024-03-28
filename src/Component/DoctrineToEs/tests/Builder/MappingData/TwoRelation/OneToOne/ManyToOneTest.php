<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TwoRelation\OneToOne;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingGroup;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

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
