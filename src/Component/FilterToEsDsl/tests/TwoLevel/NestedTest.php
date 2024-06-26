<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\TwoLevel;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class NestedTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->search->search(User::class));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][equals]=test')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][not_equals]=test')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][in][]=test&filters[][bills.testString][in][]=test2')));
        $this->assertEquals([3], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][not_in][]=test&filters[][bills.testString][not_in][]=test2')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][bills.testSmallint][lte]=2')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][bills.testSmallint][gte]=2')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][bills.testInteger][exists]=1')));
        $this->assertEquals([3], $this->search->search(User::class, $this->urlToArray('filters[][bills.testInteger][not_exists]=1')));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][starts_with]=test2')));
        $this->assertEquals([1, 2, 3], $this->search->search(User::class, $this->urlToArray('applicators[][sort][bills.id]=asc')));
        $this->assertEquals([3, 2, 1], $this->search->search(User::class, $this->urlToArray('applicators[][sort][bills.id]=desc')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

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
    }
}
