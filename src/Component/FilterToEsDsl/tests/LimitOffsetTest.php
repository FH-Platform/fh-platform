<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\ConnectionDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform\UserProvider;

class LimitOffsetTest extends TestCase
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
        $this->save([$user]);

        $user2 = new User();
        $this->save([$user2]);

        $user3 = new User();
        $this->save([$user3]);

        $this->assertEquals([1, 2, 3], $this->filterQuery->search($index));
        $this->assertEquals([1, 2], $this->filterQuery->search($index, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=0')));
        $this->assertEquals([2, 3], $this->filterQuery->search($index, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=1')));
        $this->assertEquals([], $this->filterQuery->search($index, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=3')));
        $this->assertEquals([2], $this->filterQuery->search($index, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=1')));
    }
}
