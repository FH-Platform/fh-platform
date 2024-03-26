<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneBidirectionalSelfReferencingTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $setting = $this->populateEntity(new Setting());
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

        $data = $this->dataProvider->provide($index, $user, ['setting' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $this->dataTest,
        ]), $data);
    }
}
