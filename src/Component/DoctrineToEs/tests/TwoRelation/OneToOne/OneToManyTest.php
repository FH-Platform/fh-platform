<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\TwoRelation\OneToOne;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\OneRelation\TestCaseOneRelation;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingGroup;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingMain;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToManyTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $setting = $this->populateEntity(new Setting());

        $settingItem = $this->populateEntity(new SettingItem());
        $settingItem2 = $this->populateEntity(new SettingItem());

        $settingItem->setSetting($setting);
        $settingItem2->setSetting($setting);
        $this->save([$settingItem, $settingItem2]);

        $user = $this->populateEntity(new User());
        $user->setSetting($setting);
        $this->save([$user]);

        $conf = [
            'setting' => [
                'settingItems' => []
            ]
        ];

        $mappingTestSetting = $this->mappingTest;
        $mappingTestSetting['settingItems'] = [
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
        $dataTestSetting['settingItems'] = [$this->dataTest, $dataTestItem2];

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $dataTestSetting,
        ]), $data);
    }
}
