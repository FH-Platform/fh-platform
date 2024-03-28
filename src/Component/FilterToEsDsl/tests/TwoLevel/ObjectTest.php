<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\TwoLevel;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\ConnectionDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;
use FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform\UserProvider;

class ObjectTest extends TestCase
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
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testString][equals]=test')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testString][not_equals]=test')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testString][in][]=test&filters[][setting.testString][in][]=test2')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testString][not_in][]=test&filters[][setting.testString][not_in][]=test2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testSmallint][lte]=2')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testSmallint][gte]=2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testInteger][exists]=1')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testInteger][not_exists]=1')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.testString][starts_with]=test2')));
        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][setting.id]=asc')));
        $this->assertEquals([3, 2, 1], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][setting.id]=desc')));
    }

    private function prepareData()
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName(User::class)[0];
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
    }
}
