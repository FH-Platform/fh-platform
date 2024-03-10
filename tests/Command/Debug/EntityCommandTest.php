<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Debug;

use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;

class EntityCommandTest extends TestCase
{
    public function testSomething(): void
    {
        $this->prepareUsers();

        $this->commandHelper->runCommand(['command' => 'symfony-es:debug:entity', 'class-name' => User::class, 'id' => 1]);

        $this->assertEquals(1, 1);
    }

    private function prepareUsers()
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setNameString('name_'.random_int(1, 10000));
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
