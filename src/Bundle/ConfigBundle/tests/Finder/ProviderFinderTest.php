<?php

namespace FHPlatform\ConfigBundle\Tests\Finder;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity2;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated2;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex2;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderEntity_LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderEntityRelated_LogEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Provider\ProviderIndex_LogIndex;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Util\Finder\ProviderFinder;

class ProviderFinderTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderEntity_LogEntity::class,
            ProviderIndex_LogIndex::class,
            ProviderEntityRelated_LogEntityRelated::class,
        ];

        parent::setUp();
    }

    public function testService(): void
    {
        /** @var ProviderFinder $providerFinder */
        $providerFinder = $this->container->get(ProviderFinder::class);

        // EXISTS
        // find provider entity
        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntity(LogEntity::class)));
        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntityRelated(LogEntity::class)));
        $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderIndex(LogEntity::class)));

        // find provider entity_related
        $this->assertEquals(ProviderEntityRelated_LogEntityRelated::class, get_class($providerFinder->findProviderEntityRelated(LogEntityRelated::class)));

        // find provider index
        $this->assertEquals(ProviderIndex_LogIndex::class, get_class($providerFinder->findProviderIndex(LogIndex::class)));

        // NOT_EXISTS -> ERROR
        // find provider entity
        try {
            $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntity(LogEntity2::class)));
        } catch (ProviderForClassNameNotExists $e) {
            $this->assertEquals(ProviderForClassNameNotExists::class, get_class($e));
        }
        try {
            $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderEntityRelated(LogEntity2::class)));
        } catch (ProviderForClassNameNotExists $e) {
            $this->assertEquals(ProviderForClassNameNotExists::class, get_class($e));
        }
        try {
            $this->assertEquals(ProviderEntity_LogEntity::class, get_class($providerFinder->findProviderIndex(LogEntity2::class)));
        } catch (ProviderForClassNameNotExists $e) {
            $this->assertEquals(ProviderForClassNameNotExists::class, get_class($e));
        }

        // find provider entity_related
        try {
            $this->assertEquals(ProviderEntityRelated_LogEntityRelated::class, get_class($providerFinder->findProviderEntityRelated(LogEntityRelated2::class)));
        } catch (ProviderForClassNameNotExists $e) {
            $this->assertEquals(ProviderForClassNameNotExists::class, get_class($e));
        }

        // find provider index
        try {
            $this->assertEquals(ProviderIndex_LogIndex::class, get_class($providerFinder->findProviderIndex(LogIndex2::class)));
        } catch (ProviderForClassNameNotExists $e) {
            $this->assertEquals(ProviderForClassNameNotExists::class, get_class($e));
        }

        // NOT_EXISTS -> null
        $this->assertEquals(null, $providerFinder->findProviderEntity(LogEntity2::class, false));
        $this->assertEquals(null, $providerFinder->findProviderEntityRelated(LogEntity2::class, false));
        $this->assertEquals(null, $providerFinder->findProviderIndex(LogEntity2::class, false));

        // find provider entity_related
        $this->assertEquals(null, $providerFinder->findProviderEntityRelated(LogEntityRelated2::class, false));

        // find provider index
        $this->assertEquals(null, $providerFinder->findProviderIndex(LogIndex2::class, false));
    }
}
