<?php

namespace FHPlatform\Component\PersistenceEloquent\Tests\Persistence;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceEloquent\Tests\TestCase;
use FHPlatform\Component\PersistenceEloquent\Tests\Util\Entity\User;

class PersistenceEloquentTest extends TestCase
{
    public function testHelper(): void
    {
        /** @var PersistenceInterface $persistence */
        $persistence = $this->container->get(PersistenceInterface::class);

        $user = new User();
        $user->id = 2;

        $user->save();

        $this->assertEquals('id', $persistence->getIdentifierName($user));
        $this->assertEquals(2, $persistence->getIdentifierValue($user));
    }
}
