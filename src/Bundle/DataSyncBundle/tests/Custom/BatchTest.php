<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Custom;

use FHPlatform\Bundle\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\Bundle\ConfigBundle\Config\ConfigProvider;
use FHPlatform\Bundle\DataSyncBundle\Tests\TestCase;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

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
