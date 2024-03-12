<?php

namespace FHPlatform\ConfigBundle\Tests\Finder;

use FHPlatform\ConfigBundle\Finder\ProviderFinder;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderEntity_LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderEntityRelated_LogEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderIndex_LogIndex;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;

class ProviderFinderTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            ProviderEntity_LogEntity::class,
            ProviderIndex_LogIndex::class,
            ProviderEntityRelated_LogEntityRelated::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ProviderFinder $providerFinder */
        $providerFinder = $this->container->get(ProviderFinder::class);

        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntity(LogEntity::class)));
        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntityRelated(LogEntity::class)));
        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderIndex(LogEntity::class)));

        $this->assertEquals(ProviderEntityRelated_LogEntityRelated::class, get_class($providerFinder->findProviderEntityRelated(LogEntityRelated::class)));

        $this->assertEquals(ProviderIndex_LogIndex::class, get_class($providerFinder->findProviderIndex(LogIndex::class)));
    }
}
