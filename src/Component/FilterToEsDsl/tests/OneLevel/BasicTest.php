<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\OneLevel;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class));
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][equals]=test')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][not_equals]=test')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][in][]=test&filters[][testString][in][]=test2')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][not_in][]=test&filters[][testString][not_in][]=test2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testSmallint][lte]=2')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testSmallint][gte]=2')));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][exists]=1')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][not_exists]=1')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][starts_with]=test2')));
        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][id]=asc')));
        $this->assertEquals([3, 2, 1], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][sort][id]=desc')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

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
    }
}
