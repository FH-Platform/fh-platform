<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

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

        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('applicators[][sort][id]=asc')));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][testString][equals]=test_string')));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][equals]=test_string')));

        $this->entityManager->remove($bill);
        $this->entityManager->flush();

        $this->assertEquals([1, 2], $this->search->search(User::class, $this->urlToArray('applicators[][sort][id]=asc')));
        $this->assertEquals([1], $this->search->search(User::class, $this->urlToArray('filters[][testString][equals]=test_string')));
        $this->assertEquals([], $this->search->search(User::class, $this->urlToArray('filters[][bills.testString][equals]=test_string')));
    }
}
