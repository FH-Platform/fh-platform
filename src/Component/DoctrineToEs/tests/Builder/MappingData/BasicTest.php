<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, true, '', '', []);
        $mapping = $this->mappingProvider->build($index, []);

        $user = $this->populateEntity(new User());

        $this->assertEquals($this->mappingTest, $mapping);

        $data = $this->dataProvider->build($index, $user, []);
        $this->assertEquals($this->dataTest, $data);
    }
}
