<?php

namespace FHPlatform\ConfigBundle\Tests\Service\Namer;

use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Util\Namer\IndexNamer;

class IndexNamerTest extends TestCase
{
    public function testSomething(): void
    {
        $name = new IndexNamer();

        $this->assertEquals('user', $name->getName('App\Entity\User'));
        $this->assertEquals('user_two', $name->getName('App\Entity\UserTwo'));
        $this->assertEquals('user_two', $name->getName('App\Entity\User_Two'));
        $this->assertEquals('user', $name->getName('App\Entity\Namespace\User'));
    }
}
