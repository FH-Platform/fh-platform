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
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=0')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=1')));
        $this->assertEquals([], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=3')));
        $this->assertEquals([2], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=1')));
    }

    private function prepareData()
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);

        $user = new User();
        $user2 = new User();
        $user3 = new User();

        $this->save([$user, $user2, $user3]);
    }
}
