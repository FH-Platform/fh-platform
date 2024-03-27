<?php

namespace FHPlatform\Component\Filter\Tests\TwoLevel;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Es\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;

use FHPlatform\Component\Filter\FilterQuery;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\Es\UserProviderEntity;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class ObjectTest extends TestCase
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

        $setting = new Setting();
        $setting->setTestString('test');
        $setting->setTestText('testsomething');
        $setting->setTestSmallint(1);
        $setting->setTestInteger(1);
        $this->save([$setting]);
        $user = new User();
        $user->setSetting($setting);
        $this->save([$user]);

        $setting2 = new Setting();
        $setting2->setTestString('test2');
        $setting2->setTestText('test2something');
        $setting2->setTestSmallint(2);
        $setting2->setTestInteger(2);
        $this->save([$setting2]);
        $user2 = new User();
        $user2->setSetting($setting2);
        $this->save([$user2]);

        $setting3 = new Setting();
        $setting3->setTestString('test22something');
        $setting3->setTestText('test3');
        $setting3->setTestSmallint(3);
        $this->save([$setting3]);
        $user3 = new User();
        $user3->setSetting($setting3);
        $this->save([$user3]);

        $this->save([$setting, $setting2, $setting3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters['setting.testString']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testString']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testString']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testString']['not_in'] = ['test', 'test2'];
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testSmallint']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testSmallint']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testInteger']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testInteger']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters['setting.testString']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $applicators = [];
        $applicators['id']['sort'] = 'asc';
        $this->assertEquals([1, 2, 3], $filterQuery->search($index, ['applicators' => $applicators]));

        $applicators = [];
        $applicators['id']['sort'] = 'desc';
        $this->assertEquals([3, 2, 1], $filterQuery->search($index, ['applicators' => $applicators]));
    }
}
