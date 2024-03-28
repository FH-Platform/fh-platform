<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\Other;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class LimitOffsetTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class));
        $this->assertEquals([1, 2], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=0')));
        $this->assertEquals([2, 3], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=2&applicators[][offset]=1')));
        $this->assertEquals([], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=3')));
        $this->assertEquals([2], $this->filterQuery->search(User::class, $this->urlToArray('applicators[][limit]=1&applicators[][offset]=1')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

        $user = new User();
        $user2 = new User();
        $user3 = new User();

        $this->save([$user, $user2, $user3]);
    }
}
