<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\ThreeLevel\Object;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class NestedTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->search->search(User::class));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testString][equals]=test')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testString][not_equals]=test')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testString][in][]=test&filters[][setting.settingItems.testString][in][]=test2')));
        $this->assertEquals([3], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testString][not_in][]=test&filters[][setting.settingItems.testString][not_in][]=test2')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testSmallint][lte]=2')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testSmallint][gte]=2')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testInteger][exists]=1')));
        $this->assertEquals([3], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testInteger][not_exists]=1')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][setting.settingItems.testString][starts_with]=test2')));
        $this->assertEquals([1, 2, 3], $this->search->search(User::class, $this->urlToArray('applicators[][sort][setting.settingItems.id]=asc')));
        $this->assertEquals([3, 2, 1], $this->search->search(User::class, $this->urlToArray('applicators[][sort][setting.settingItems.id]=desc')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

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
    }
}
