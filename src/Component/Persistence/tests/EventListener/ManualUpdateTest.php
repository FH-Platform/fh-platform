<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Persistence\Event\EventManager;
use FHPlatform\Component\Persistence\Tests\TestCase;
use FHPlatform\Component\Persistence\Tests\Util\Entity\User;

class ManualUpdateTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->container->get(EventManager::class);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));

        // delete
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->entityManager->createQuery('DELETE FROM '.User::class.' e WHERE e.id = 1')->execute();
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));
        $eventManager->syncByClassName(User::class, [1]);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));

        // update
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);

        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->entityManager->createQuery('UPDATE '.User::class." e SET e.nameString = 'test2' WHERE e.id = 2")->execute();
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test2'));
        $eventManager->syncByClassName(User::class, [2]);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test2'));

        // update -> TODO
    }
}
