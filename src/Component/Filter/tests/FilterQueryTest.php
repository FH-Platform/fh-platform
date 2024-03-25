<?php

namespace FHPlatform\Component\Filter;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\Entity\User;
use FHPlatform\Component\Filter\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Component\Filter\Tests\Util\Es\Config\Provider\UserProviderEntity;

class FilterQueryTest extends TestCase
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
        $user = new User();
        $user->setNameString('test');

        $user2 = new User();
        $user2->setNameString('test2');

        $user3 = new User();
        $user3->setNameString('test3');

        $this->save([$user, $user2, $user3]);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $this->assertEquals(1, 1);
    }
}
