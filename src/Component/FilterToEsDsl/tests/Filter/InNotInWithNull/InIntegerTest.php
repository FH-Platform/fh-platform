<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\FilterToEsDsl\InNotInWithNull;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\ConnectionDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\DataDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\FHPlatform\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\FilterToEsDsl\Tests\TestCase;
use FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform\UserProvider;

class InIntegerTest extends TestCase
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
        $this->assertEquals([1], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][in][]=1')));
        $this->assertEquals([3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][in][]=null')));
        $this->assertEquals([1, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][in][]=1&filters[][testInteger][in][]=null')));
        $this->assertEquals([1, 2, 3], $this->filterQuery->search(User::class, $this->urlToArray('filters[][testInteger][in][]=1&filters[][testInteger][in][]=2&filters[][testInteger][in][]=null')));
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
