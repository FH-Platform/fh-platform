<?php

namespace FHPlatform\ConfigBundle\Tests\Tag;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Provider\ProviderEntity_User;
use FHPlatform\ConfigBundle\Tests\TestCase;

class DecorateMappingItemTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderConnection_Default::class,
            ProviderEntity_User::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        /** @var IndexFetcher $indexFetcher */
        $indexFetcher = $this->container->get(IndexFetcher::class);

        $this->assertEquals(1, 1);

        dd($indexFetcher->fetch(User::class));
    }
}
