<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\MappingData\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $location = $this->populateEntity(new Location());
        $user = $this->populateEntity(new User());
        $user->setLocation($location);
        $this->save([$user]);

        $mapping = $this->mappingProvider->provide($index, ['location' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'location' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $this->dataProvider->provide($index, $user, ['location' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'location' => $this->dataTest,
        ]), $data);
    }
}
