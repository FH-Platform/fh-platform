<?php

namespace FHPlatform\DataSyncBundle\Tests\Custom;

use FHPlatform\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

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
