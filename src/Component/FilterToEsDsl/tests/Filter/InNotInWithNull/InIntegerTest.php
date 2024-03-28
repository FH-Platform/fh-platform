<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\FilterToEsDsl\InNotInWithNull;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\ConnectionDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;
use FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform\UserProvider;

class InIntegerTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProvider::class,
            DataDecorator::class,
            MappingDecorator::class,
            EntityRelatedDecorator::class,
            ConnectionDecorator::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];
        $this->indexClient->recreateIndex($index);

        $user = new User();
        $user->setTestInteger(1);
        $this->save([$user]);

        $user2 = new User();
        $user2->setTestInteger(2);
        $this->save([$user2]);

        $user3 = new User();
        $user3->setTestInteger(null);
        $this->save([$user3]);

        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class));

        $filters = [];
        $filters[]['testInteger']['in'] = [];
        $this->assertEquals([], $this->filterQuery->search(User::class, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['in'] = [1];
        $this->assertEquals([1], $this->filterQuery->search(User::class, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['in'] = [null];
        $this->assertEquals([3], $this->filterQuery->search(User::class, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['in'] = [1, null];
        $this->assertEquals([1, 3], $this->filterQuery->search(User::class, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['in'] = [1, 2, null];
        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class, ['filters' => $filters]));
    }
}
