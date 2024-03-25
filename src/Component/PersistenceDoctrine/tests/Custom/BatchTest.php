<?php

namespace FHPlatform\Component\PersistenceDoctrine\Tests\Custom;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Entity\User;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Provider\UserProviderEntity;

class BatchTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);

        $this->prepareUsers();

        $this->entityManager->flush();

        $this->assertEquals(1, 1);
    }

    private function prepareUsers(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setNameString('name_'.$i);
            $this->entityManager->persist($user);
        }
    }
}
