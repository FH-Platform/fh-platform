<?php

namespace FHPlatform\ClientBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Elastica\Query;
use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Client\Index\IndexNameClient;
use FHPlatform\ClientBundle\Client\Query\QueryClient;
use FHPlatform\ClientBundle\Tests\Util\Helper\CommandHelper;
use FHPlatform\ClientBundle\Tests\Util\Helper\TaggedProviderMock;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Filesystem;

class TestCase extends KernelTestCase
{
    private static array $events = [];

    protected Container $container;
    protected CommandHelper $commandHelper;
    protected EntityManagerInterface $entityManager;

    protected IndexClient $indexClient;
    protected IndexNameClient $indexNameClient;
    protected QueryClient $queryClient;
    protected DataClient $dataClient;

    protected function setUp(): void
    {
        $this->prepareContainer();
        $this->prepareServices();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        TaggedProviderMock::$included = [];
    }

    protected function prepareContainer(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $this->container = static::getContainer();

        // (3) - CommandHelper
        /** @var CommandHelper $commandHelper */
        $commandHelper = $this->container->get(CommandHelper::class);
        $this->commandHelper = $commandHelper;

        // (4) - EntityManagerInterface
        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->migrateDb();
    }

    protected function prepareServices(): void
    {
        $this->indexClient = $this->container->get(IndexClient::class);
        $this->indexNameClient = $this->container->get(IndexNameClient::class);
        $this->queryClient = $this->container->get(QueryClient::class);
        $this->dataClient = $this->container->get(DataClient::class);
    }

    protected function eventsStartListen(string $eventClass): void
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $eventDispatcher->addListener($eventClass, function ($event) use ($eventClass): void {
            self::$events[$eventClass][] = $event;
        });
    }

    protected function eventsGet(string $eventClass): array
    {
        return self::$events[$eventClass] ?? [];
    }

    protected function eventsClear($eventClass): void
    {
        self::$events[$eventClass] = [];
    }

    private function migrateDb()
    {
        $filesystem = new Filesystem();
        $filesystem->remove('var/database.db3');

        // updating a schema in sqlite database
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($metaData);
    }

    protected function save(array $entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    protected function delete(array $entities): void
    {
        foreach ($entities as $entity) {
            $this->entityManager->remove($entity);
        }

        $this->entityManager->flush();
    }

    protected function findEsBy($className, $key, $value): array
    {
        return $this->queryClient->getResults($className, (new Query())->setQuery(new Query\MatchQuery($key, $value)));
    }
}
