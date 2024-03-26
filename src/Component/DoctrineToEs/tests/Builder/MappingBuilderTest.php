<?php

namespace FHPlatform\Component\DoctrineToEs\Builder;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\Config\Provider\UserProviderEntity;

class MappingBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var MappingProvider $mappingProvider */
        $mappingProvider = $this->container->get(MappingProvider::class);

        $this->assertEquals(1, 1);
    }
}
