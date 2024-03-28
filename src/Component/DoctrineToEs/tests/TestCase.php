<?php

namespace FHPlatform\Component\DoctrineToEs\Tests;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class TestCase extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected function prepareIndex(): mixed
    {
        return new Index(new Connection('test', 'test', []), User::class, true, '', '', []);
    }
}
