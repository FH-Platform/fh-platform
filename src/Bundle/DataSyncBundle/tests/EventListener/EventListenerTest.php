<?php

namespace FHPlatform\DataSyncBundle\Tests\EventListener;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Provider\ConnectionsProvider;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

class EventListenerTest extends TestCase
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
        /** @var ConnectionsProvider $connectionsProvider */
        $connectionsProvider = $this->container->get(ConnectionsProvider::class);
        $index = $connectionsProvider->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // create
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // update
        $user->setNameString('test2');
        $this->save([$user]);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test2'));

        // delete
        $this->delete([$user]);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));
    }
}
