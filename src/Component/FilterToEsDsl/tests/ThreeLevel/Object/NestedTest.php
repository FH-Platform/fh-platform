<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\ThreeLevel\Object;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\FilterToEsDsl\FilterQuery;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;
use FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform\UserProvider;

class NestedTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProvider::class,
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
        $this->save([$setting]);
        $settingItem = new SettingItem();
        $settingItem->setTestString('test');
        $settingItem->setTestText('testsomething');
        $settingItem->setTestSmallint(1);
        $settingItem->setTestInteger(1);
        $settingItem->setSetting($setting);
        $this->save([$settingItem]);
        $user = new User();
        $user->setSetting($setting);
        $this->save([$user]);

        $setting2 = new Setting();
        $this->save([$setting2]);
        $settingItem2 = new SettingItem();
        $settingItem2->setTestString('test2');
        $settingItem2->setTestText('test2something');
        $settingItem2->setTestSmallint(2);
        $settingItem2->setTestInteger(2);
        $settingItem2->setSetting($setting2);
        $this->save([$settingItem2]);
        $user2 = new User();
        $user2->setSetting($setting2);
        $this->save([$user2]);

        $setting3 = new Setting();
        $this->save([$setting3]);
        $settingItem3 = new SettingItem();
        $settingItem3->setTestString('test22something');
        $settingItem3->setTestText('test3');
        $settingItem3->setTestSmallint(3);
        $settingItem3->setSetting($setting3);
        $this->save([$settingItem3]);
        $user3 = new User();
        $user3->setSetting($setting3);
        $this->save([$user3]);

        $this->save([$setting, $setting2, $setting3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters[]['setting.settingItems.testString']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testString']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testString']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testString']['not_in'] = ['test', 'test2'];
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testSmallint']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testSmallint']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testInteger']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testInteger']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingItems.testString']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $applicators = [];
        $applicators[]['setting.settingItems.id']['sort'] = 'asc';
        $this->assertEquals([1, 2, 3], $filterQuery->search($index, ['applicators' => $applicators]));

        $applicators = [];
        $applicators[]['setting.settingItems.id']['sort'] = 'desc';
        $this->assertEquals([3, 2, 1], $filterQuery->search($index, ['applicators' => $applicators]));
    }
}
