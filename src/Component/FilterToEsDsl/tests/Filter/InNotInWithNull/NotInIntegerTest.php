<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\FilterToEsDsl\InNotInWithNull;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class NotInIntegerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->search->search(User::class));
        $this->assertEquals([2, 3], $this->search->search(User::class, $this->urlToArray('filters[][testInteger][not_in][]=1')));
        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('filters[][testInteger][not_in][]=null')));
        $this->assertEquals([2], $this->search->search(User::class, $this->urlToArray('filters[][testInteger][not_in][]=1&filters[][testInteger][not_in][]=null')));
        $this->assertEquals([], $this->search->search(User::class, $this->urlToArray('filters[][testInteger][not_in][]=1&&filters[][testInteger][not_in][]=2&filters[][testInteger][not_in][]=null')));
    }

    private function prepareData(): void
    {
        $this->recreateIndex(User::class);

        $user = new User();
        $user->setTestInteger(1);
        $this->save([$user]);

        $user2 = new User();
        $user2->setTestInteger(2);
        $this->save([$user2]);

        $user3 = new User();
        $user3->setTestInteger(null);
        $this->save([$user3]);
    }
}
