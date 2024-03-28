<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $setting = $this->populateEntity(new Setting());
        $user = $this->populateEntity(new User());
        $user->setSetting($setting);
        $this->save([$user]);

        $mapping = $this->mappingProvider->build($index, ['setting' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $this->dataProvider->build($index, $user, ['setting' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $this->dataTest,
        ]), $data);
    }
}
