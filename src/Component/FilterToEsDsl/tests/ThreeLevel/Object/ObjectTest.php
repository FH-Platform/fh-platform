<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\ThreeLevel\Object;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\SettingGroup;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class ObjectTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class));
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testString][equals]=test')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testString][not_equals]=test')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testString][in][]=test&filters[][setting.settingGroup.testString][in][]=test2')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testString][not_in][]=test&filters[][setting.settingGroup.testString][not_in][]=test2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testSmallint][lte]=2')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testSmallint][gte]=2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testInteger][exists]=1')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testInteger][not_exists]=1')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][setting.settingGroup.testString][starts_with]=test2')));
        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][setting.settingGroup.id]=asc')));
        $this->assertEquals([3, 2, 1], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][setting.settingGroup.id]=desc')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

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
    }
}
