<?php

namespace FHPlatform\Component\DoctrineToEs\MappingData\Basic;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);
        $mapping = $this->mappingProvider->provide($index, []);

        $user = $this->populateEntity(new User());

        $this->assertEquals($this->mappingTest, $mapping);

        $data = $this->dataProvider->provide($index, $user, []);
        $this->assertEquals($this->dataTest, $data);
    }
}
