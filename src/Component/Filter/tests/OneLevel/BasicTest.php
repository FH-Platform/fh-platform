<?php

namespace FHPlatform\Component\Filter\Tests\OneLevel;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Es\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;
use FHPlatform\Component\Filter\FilterQuery;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\Es\UserProviderEntity;

class BasicTest extends TestCase
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
        $user->setTestString('test');
        $user->setTestText('testsomething');
        $user->setTestSmallint(1);
        $user->setTestInteger(1);

        $user2 = new User();
        $user2->setTestString('test2');
        $user2->setTestText('test2something');
        $user2->setTestSmallint(2);
        $user2->setTestInteger(2);

        $user3 = new User();
        $user3->setTestString('test22something');
        $user3->setTestText('test3');
        $user3->setTestSmallint(3);

        $this->save([$user, $user2, $user3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters[]['testString']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testString']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testString']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testString']['not_in'] = ['test', 'test2'];
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testSmallint']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testSmallint']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testInteger']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['testString']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $applicators = [];
        $applicators[]['id']['sort'] = 'asc';
        $this->assertEquals([1, 2, 3], $filterQuery->search($index, ['applicators' => $applicators]));

        $applicators = [];
        $applicators[]['id']['sort'] = 'desc';
        $this->assertEquals([3, 2, 1], $filterQuery->search($index, ['applicators' => $applicators]));
    }
}
