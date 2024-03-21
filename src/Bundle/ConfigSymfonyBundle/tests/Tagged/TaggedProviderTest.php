<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Tagged;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\DecoratorEntityDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\DecoratorEntityRelatedDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\DecoratorIndexDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\ProviderConnectionDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\ProviderEntityDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\ProviderEntityRelatedDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util\ProviderIndexDefault;
use FHPlatform\ConfigSymfonyBundle\Tests\TestCase;

class TaggedProviderTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderConnectionDefault::class,
            ProviderEntityDefault::class,
            ProviderEntityRelatedDefault::class,
            ProviderIndexDefault::class,
            DecoratorEntityDefault::class,
            DecoratorEntityRelatedDefault::class,
            DecoratorIndexDefault::class,
        ];

        parent::setUp();
    }

    public function testService(): void
    {
        /** @var ConfigProvider $taggedProvider */
        $taggedProvider = $this->container->get(ConfigProvider::class);

        $this->assertEquals(1, count($taggedProvider->getConnections()));
        $this->assertEquals(ProviderConnectionDefault::class, get_class($taggedProvider->getConnections()[0]));

        $this->assertEquals(1, count($taggedProvider->getProvidersEntity()));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getProvidersEntity()[0]));

        $this->assertEquals(2, count($taggedProvider->getProvidersEntityRelated()));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getProvidersEntityRelated()[0]));
        $this->assertEquals(ProviderEntityRelatedDefault::class, get_class($taggedProvider->getProvidersEntityRelated()[1]));

        $this->assertEquals(2, count($taggedProvider->getProvidersIndex()));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getProvidersIndex()[0]));
        $this->assertEquals(ProviderIndexDefault::class, get_class($taggedProvider->getProvidersIndex()[1]));

        $this->assertEquals(2, count($taggedProvider->getDecoratorsEntity()));
        $this->assertEquals(DecoratorEntityDefault::class, get_class($taggedProvider->getDecoratorsEntity()[0]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsEntity()[1]));

        $this->assertEquals(3, count($taggedProvider->getDecoratorsEntityRelated()));
        $this->assertEquals(DecoratorEntityRelatedDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[0]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[1]));
        $this->assertEquals(ProviderEntityRelatedDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[2]));

        $this->assertEquals(3, count($taggedProvider->getDecoratorsIndex()));
        $this->assertEquals(DecoratorIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[0]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsIndex()[1]));
        $this->assertEquals(ProviderIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[2]));
    }

    public function testExcluded(): void
    {
        /** @var ConfigProvider $taggedProvider */
        $taggedProvider = $this->container->get(ConfigProvider::class);

        $this->assertEquals(3, count($taggedProvider->getDecoratorsIndex()));
        $this->assertEquals(DecoratorIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[0]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsIndex()[1]));
        $this->assertEquals(ProviderIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[2]));

        ConfigProvider::$excludedClasses = [
            DecoratorIndexDefault::class,
        ];

        $this->assertEquals(2, count($taggedProvider->getDecoratorsIndex()));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsIndex()[0]));
        $this->assertEquals(ProviderIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[1]));
    }
}