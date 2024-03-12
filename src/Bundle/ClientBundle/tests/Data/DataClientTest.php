<?php

namespace FHPlatform\ClientBundle\Tests\Data;

use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ClientBundle\Tests\TestCase;
use FHPlatform\ClientBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;

class DataClientTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertEquals(1, 1);

        /** @var DataClient $dataClient */
        $dataClient = $this->container->get(DataClient::class);

        $connection = new Connection('default', 'prefix_', [
            'servers' => [
                ['host' => 'elasticsearch', 'port' => '9200'],
            ],
        ]);

        $connection2 = new Connection('default2', 'prefix_', [
            'servers' => [
                ['host' => 'elasticsearch2', 'port' => '9200'],
            ],
        ]);

        $index = new Index(User::class, $connection, 'user', [], [], []);
        $index2 = new Index(User::class, $connection2, 'user', [], [], []);

        $user = new User();
        $user->setNameString('test');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->dataClient->upsertBatch([
            new Entity($user, User::class, 1, $index, ['test' => '1'], true),
            new Entity($user, User::class, 2, $index, ['test2' => '2'], true),
            new Entity($user, User::class, 3, $index2, ['test3' => '3'], true),
        ]);
    }
}
