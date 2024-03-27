<?php

namespace FHPlatform\Component\Filter\Tests\TwoLevel;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Es\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;

use FHPlatform\Component\Filter\FilterQuery;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\Es\UserProviderEntity;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class NestedTest extends TestCase
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
        $this->save([$user]);
        $bill = new Bill();
        $bill->setUser($user);
        $bill->setTestString('test');
        $bill->setTestText('testsomething');
        $bill->setTestSmallint(1);
        $bill->setTestInteger(1);
        $this->save([$bill]);

        $user2 = new User();
        $this->save([$user2]);
        $bill2 = new Bill();
        $bill2->setUser($user2);
        $bill2->setTestString('test2');
        $bill2->setTestText('test2something');
        $bill2->setTestSmallint(2);
        $bill2->setTestInteger(2);
        $this->save([$bill2]);

        $user3 = new User();
        $this->save([$user3]);
        $bill3 = new Bill();
        $bill3->setUser($user3);
        $bill3->setTestString('test22something');
        $bill3->setTestText('test3');
        $bill3->setTestSmallint(3);
        $this->save([$bill3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters['bills.testString']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testString']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testString']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testString']['not_in'] = ['test', 'test2'];
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testSmallint']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testSmallint']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testInteger']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testInteger']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['bills.testString']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $applicators = [];
        $applicators['bills.id']['sort'] = 'asc';
        $this->assertEquals([1, 2, 3], $filterQuery->search($index, ['applicators' => $applicators]));

        $applicators = [];
        $applicators['bills.id']['sort'] = 'desc';
        $this->assertEquals([3, 2, 1], $filterQuery->search($index, ['applicators' => $applicators]));
    }
}