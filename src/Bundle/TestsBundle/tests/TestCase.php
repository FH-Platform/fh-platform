<?php

namespace FHPlatform\Bundle\TestsBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FHPlatform\Bundle\TestsBundle\Tests\Util\CommandHelper;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\FilterToEsDsl\Query\SearchClassName;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TestCase extends KernelTestCase
{
    private static array $events = [];

    protected Container $container;
    protected CommandHelper $commandHelper;
    protected EntityManagerInterface $entityManager;

    protected IndexManager $indexClient;
    protected QueryManager $queryClient;
    protected DataManager $dataClient;

    protected function setUp(): void
    {
        $this->prepareContainer();
        $this->prepareServices();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        ConfigProvider::$includedClasses = [];
    }

    protected function prepareContainer(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $this->container = static::getContainer();

        // (3) - CommandHelper
        $this->commandHelper = new CommandHelper(self::$kernel);

        // (4) - EntityManagerInterface
        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->migrateDb();
    }

    protected function prepareServices(): void
    {
        $this->indexClient = $this->container->get(IndexManager::class);
        $this->queryClient = $this->container->get(QueryManager::class);
        $this->dataClient = $this->container->get(DataManager::class);
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

    protected function findEsBy(string $className, $key, $value): array
    {
        return $this->container->get(SearchClassName::class)->search($className, $this->urlToArray('filters[]['.$key.'][in][]='.$value));
    }

    protected function recreateIndex(string $className): void
    {
        $index = $this->container->get(ConnectionsBuilder::class)->fetchIndexesByClassName($className)[0];
        $this->indexClient->recreateIndex($index);
    }

    protected function urlToArray($url): array
    {
        $array = [];

        parse_str($url, $array);

        return $array;
    }
}
