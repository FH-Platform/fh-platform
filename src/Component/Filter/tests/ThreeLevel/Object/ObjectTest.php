<?php

namespace FHPlatform\Component\Filter\Tests\ThreeLevel\Object;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Es\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingGroup;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;
use FHPlatform\Component\Filter\FilterQuery;
use FHPlatform\Component\Filter\Tests\TestCase;
use FHPlatform\Component\Filter\Tests\Util\Es\UserProviderEntity;

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

        $settingGroup = new SettingGroup();
        $settingGroup->setTestString('test');
        $settingGroup->setTestText('testsomething');
        $settingGroup->setTestSmallint(1);
        $settingGroup->setTestInteger(1);
        $this->save([$settingGroup]);
        $setting = new Setting();
        $setting->setSettingGroup($settingGroup);
        $this->save([$setting]);
        $user = new User();
        $user->setSetting($setting);
        $this->save([$user]);

        $settingGroup2 = new SettingGroup();
        $settingGroup2->setTestString('test2');
        $settingGroup2->setTestText('test2something');
        $settingGroup2->setTestSmallint(2);
        $settingGroup2->setTestInteger(2);
        $this->save([$settingGroup2]);
        $setting2 = new Setting();
        $setting2->setSettingGroup($settingGroup2);
        $this->save([$setting2]);
        $user2 = new User();
        $user2->setSetting($setting2);
        $this->save([$user2]);

        $settingGroup3 = new SettingGroup();
        $settingGroup3->setTestString('test22something');
        $settingGroup3->setTestText('test3');
        $settingGroup3->setTestSmallint(3);
        $this->save([$settingGroup3]);
        $setting3 = new Setting();
        $setting3->setSettingGroup($settingGroup3);
        $this->save([$setting3]);
        $user3 = new User();
        $user3->setSetting($setting3);
        $this->save([$user3]);

        $this->save([$setting, $setting2, $setting3]);

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);

        $this->assertEquals([1, 2, 3], $filterQuery->search($index));

        $filters = [];
        $filters[]['setting.settingGroup.testString']['equals'] = 'test';
        $this->assertEquals([1], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testString']['not_equals'] = 'test';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testString']['in'] = ['test', 'test2'];
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testString']['not_in'] = ['test', 'test2'];
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testSmallint']['lte'] = 2;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testSmallint']['gte'] = 2;
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testInteger']['exists'] = true;
        $this->assertEquals([1, 2], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testInteger']['not_exists'] = true;
        $this->assertEquals([3], $filterQuery->search($index, ['filters' => $filters]));

        $filters = [];
        $filters[]['setting.settingGroup.testString']['starts_with'] = 'test2';
        $this->assertEquals([2, 3], $filterQuery->search($index, ['filters' => $filters]));

        $applicators = [];
        $applicators[]['setting.settingGroup.id']['sort'] = 'asc';
        $this->assertEquals([1, 2, 3], $filterQuery->search($index, ['applicators' => $applicators]));

        $applicators = [];
        $applicators[]['setting.settingGroup.id']['sort'] = 'desc';
        $this->assertEquals([3, 2, 1], $filterQuery->search($index, ['applicators' => $applicators]));
    }
}
