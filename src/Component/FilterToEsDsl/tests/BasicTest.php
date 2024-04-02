<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->recreateIndex(User::class);

        $user = new User();
        $user->setTestString('test_string');

        $user2 = new User();
        $user2->setTestString('test_string2');

        $bill = new Bill();
        $bill->setTestString('test_string');
        $bill->setUser($user);

        $this->save([$user, $user2, $bill]);

        $this->assertEquals([1, 2], $this->filterQuery->search(User::class));
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][equals]=test_string')));
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][bills.testString][equals]=test_string')));

        dump(1111);
        $this->entityManager->remove($bill);
        $this->entityManager->flush();

        $this->assertEquals([2, 1], $this->filterQuery->search(User::class));
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testString][equals]=test_string')));
        $this->assertEquals([], $this->filterQuery->search(User::class, $this->urlToArray('filters[][bills.testString][equals]=test_string')));
    }
}
