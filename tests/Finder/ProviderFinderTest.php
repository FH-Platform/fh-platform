<?php

namespace FHPlatform\ConfigBundle\Tests\Finder;

use FHPlatform\ConfigBundle\Finder\ProviderFinder;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class ProviderFinderTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
            Util\TestProviderIndex::class,
            Util\TestProviderEntity::class,
            Util\TestProviderEntityRelated::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ProviderFinder $providerFinder */
        $providerFinder = $this->container->get(ProviderFinder::class);

        $this->assertEquals('TestProviderEntity', $providerFinder->findProviderEntity('TestProviderEntity')->getClassName());

        $this->assertEquals('TestProviderEntityRelated', $providerFinder->findProviderEntityRelated('TestProviderEntityRelated')->getClassName());
        $this->assertEquals('TestProviderEntity', $providerFinder->findProviderEntityRelated('TestProviderEntity')->getClassName());

        $this->assertEquals('TestProviderIndex', $providerFinder->findProviderIndex('TestProviderIndex')->getClassName());
        $this->assertEquals('TestProviderEntity', $providerFinder->findProviderIndex('TestProviderEntity')->getClassName());
    }
}
