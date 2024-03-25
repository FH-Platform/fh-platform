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
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];
        $this->indexClient->recreateIndex($index);

        $user = new User();
        $user->setName('test');
        $user->setName2('testsomething');
        $user->setNumber(1);
        $user->setNumber2(1);

        $user2 = new User();
        $user2->setName('test2');
        $user2->setName2('test2something');
        $user2->setNumber(2);
        $user2->setNumber2(2);

        $user3 = new User();
        $user3->setName2('test22something');
        $user3->setName('test3');
        $user3->setNumber(3);

        $this->save([$user, $user2, $user3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters['name']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['name']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['name']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['name']['not_in'] = ['test', 'test3'];
        $this->assertEquals([2], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['number']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['number']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['number2']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['number2']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, $filters));

        $filters = [];
        $filters['name2']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, $filters));
    }
}
