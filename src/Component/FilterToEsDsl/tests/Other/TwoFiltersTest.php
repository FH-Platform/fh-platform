<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\Other;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class TwoFiltersTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareData();

        $this->assertEquals([1, 2, 3], $this->search->search(User::class));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][testInteger][in][]=1&filters[][testInteger][not_in][]=3')));
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
        $user3->setTestInteger(3);
        $this->save([$user3]);
    }
}
