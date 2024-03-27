<?php

namespace FHPlatform\Component\Filter\Tests\Other;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\Filter\FilterQuery;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\FHPlatform\UserProviderEntity;

class TwoFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
            DataDecorator::class,
            MappingDecorator::class,
            EntityRelatedDecorator::class,
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
        $user3->setTestInteger(3);
        $this->save([$user3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);
        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters[]['testInteger']['in'] = [1];
        $filters[]['testInteger']['not_in'] = [3];
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));
    }
}
