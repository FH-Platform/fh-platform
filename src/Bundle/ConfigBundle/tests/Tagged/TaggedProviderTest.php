<?php

namespace FHPlatform\ConfigBundle\Tests\Tagged;

use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\DecoratorEntityDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\DecoratorEntityRelatedDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\DecoratorIndexDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\ProviderConnectionDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\ProviderEntityDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\ProviderEntityRelatedDefault;
use FHPlatform\ConfigBundle\Tests\Tagged\Util\ProviderIndexDefault;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class TaggedProviderTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
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

    public function testSomething(): void
    {
        /** @var TaggedProvider $taggedProvider */
        $taggedProvider = $this->container->get(TaggedProvider::class);

        $this->assertEquals(1, count($taggedProvider->getProvidersConnection()));
        $this->assertEquals(ProviderConnectionDefault::class, get_class($taggedProvider->getProvidersConnection()[0]));

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

        $this->assertEquals(4, count($taggedProvider->getDecoratorsEntityRelated()));
        $this->assertEquals(DecoratorEntityDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[0]));
        $this->assertEquals(DecoratorEntityRelatedDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[1]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[2]));
        $this->assertEquals(ProviderEntityRelatedDefault::class, get_class($taggedProvider->getDecoratorsEntityRelated()[3]));

        $this->assertEquals(3, count($taggedProvider->getDecoratorsIndex()));
        $this->assertEquals(DecoratorIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[0]));
        $this->assertEquals(ProviderEntityDefault::class, get_class($taggedProvider->getDecoratorsIndex()[1]));
        $this->assertEquals(ProviderIndexDefault::class, get_class($taggedProvider->getDecoratorsIndex()[2]));
    }
}
