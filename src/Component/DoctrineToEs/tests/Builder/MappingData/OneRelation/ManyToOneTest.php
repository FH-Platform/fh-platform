<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $location = $this->populateEntity(new Location());
        $user = $this->populateEntity(new User());
        $user->setLocation($location);
        $this->save([$user]);

        $mapping = $this->mappingProvider->build($index, ['location' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'location' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $this->dataProvider->build($index, $user, ['location' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'location' => $this->dataTest,
        ]), $data);
    }
}
