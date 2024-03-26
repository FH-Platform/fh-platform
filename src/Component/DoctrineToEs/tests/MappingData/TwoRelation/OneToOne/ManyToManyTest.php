<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\MappingData\TwoRelation\OneToOne;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMeta;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToManyTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $setting = $this->populateEntity(new Setting());

        $settingMeta = $this->populateEntity(new SettingMeta());
        $settingMeta2 = $this->populateEntity(new SettingMeta());

        $settingMeta->setSettings(new ArrayCollection([$setting]));
        $settingMeta2->setSettings(new ArrayCollection([$setting]));

        $this->save([$settingMeta, $settingMeta2]);

        $user = $this->populateEntity(new User());
        $user->setSetting($setting);
        $this->save([$user]);

        $conf = [
            'setting' => [
                'settingMetas' => [],
            ],
        ];

        $mappingTestSetting = $this->mappingTest;
        $mappingTestSetting['settingMetas'] = [
            'type' => 'nested',
            'properties' => $this->mappingTest,
        ];

        $mapping = $this->mappingProvider->provide($index, $conf);
        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $mappingTestSetting,
            ],
        ]), $mapping);

        $data = $this->dataProvider->provide($index, $user, $conf);

        $dataTestItem2 = $this->dataTest;
        $dataTestItem2['id'] = 2;
        $dataTestSetting = $this->dataTest;
        $dataTestSetting['settingMetas'] = [$this->dataTest, $dataTestItem2];

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $dataTestSetting,
        ]), $data);
    }
}
